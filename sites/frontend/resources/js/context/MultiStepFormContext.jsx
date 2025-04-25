import { createContext, useContext, useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

const MultiStepFormContext = createContext();

const axiosConfig = {
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'multipart/form-data'
  }
};

export const MultiStepFormProvider = ({ children }) => {
  const [quote, setQuote] = useState(null);
  const [formData, setFormData] = useState({});
  const [customer, setCustomer] = useState(null);
  const [order, setOrder] = useState(null);
  const [payment, setPayment] = useState(false);
  const [error, setError] = useState(false);

  const [productOptions, setProductOptions] = useState({
    couchOptions: {},
    countryOptions: [],
  });

  const stripe = useStripe();
  const elements = useElements();

  // Fetch the options from your API endpoint
  const fetchProductOptions = async () => {
    try {
      const response = await axios.get('/couch/options', axiosConfig);
      if (response.data.error) {
        const err = new Error('Options fetch error');
        err.details = response.data.error.errors;
        throw err;
      }
      console.log('response', response);
      setProductOptions(response.data.options);
    } catch (error) {
      return Promise.reject(error.details || error.message);
    }
  };

  // Call fetchProductOptions when the provider mounts
  useEffect(() => {
    fetchProductOptions();
  }, []);

  // Merge new data into the global form data
  const updateFormData = (newData) => {
    setFormData((prevData) => ({ ...prevData, ...newData }));
  };

  // Reset all form data and state
  const resetFormData = () => {
    setQuote(null);
    setCustomer(null);
    setPayment(null);
    setOrder(null);
    setFormData({});
  };

  const createPayment = async (checkoutFormData) => {
    const paymentData = { quote, customer };
    try {
    
      const response = await axios.post('/customer/payment', paymentData, axiosConfig);
      const { clientSecret, payment: paymentResponse } = response.data;

      // Ensure that the card element is available
      const cardElement = elements.getElement(CardElement);
      if (!cardElement) {
        throw new Error('Card element not found.');
      }

      // Confirm the card payment with Stripe
      const result = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: cardElement,
          billing_details: { name: paymentData.customer.firstName + ' ' + paymentData.customer.surname },
        },
      });

      if (result.error) {
        throw new Error(result.error.message);
      }
      let processResponse;
      if (result.paymentIntent?.status === 'succeeded') {
        paymentData.payment = result;
        processResponse = await axios.post('/customer/process', paymentData, axiosConfig);
      }
     
      if (processResponse.data.error) {
        const err = new Error('Order error');
        err.details = processResponse.data.error.errors;
        throw err;
      }
      setPayment(paymentData.payment);
      console.log('payment',payment);
      setOrder(processResponse.data.order);
      console.log('order',order);
      router.visit(processResponse.data.redirect);
      return processResponse.data.order;
    } catch (error) {
      console.log('error',error);
      setError(error.details || error.message);
      return Promise.reject(error.details || error.message);
    }
  };

  const createCustomer = async (customerFormData) => {
    try {
      customerFormData.quoteId = quote.quoteId;;
      const response = await axios.post('/customer/create', customerFormData, axiosConfig);
      if (response.data.error) {
        const err = new Error('Customer error');
        err.details = response.data.error.errors;
        throw err;
      }
      setCustomer(response.data.customer);
      router.visit(response.data.redirect);
      return response.data.customer;
    } catch (error) {
      return Promise.reject(error.details || error.message);
    }
  };

  const fetchQuote = async (quoteFormData) => {
    try {
      const response = await axios.post('/customer/quote', quoteFormData, axiosConfig);
      if (response.data.error) {
        const err = new Error('Quote fetch error');
        err.details = response.data.error.errors;
        throw err;
      }
      setQuote(response.data.quote);
      router.visit(response.data.redirect);
      return response.data.quote;
    } catch (error) {
      return Promise.reject(error.details || error.message);
    }
  };

  return (
    <MultiStepFormContext.Provider
      value={{
        quote,
        customer,
        formData,
        updateFormData,
        resetFormData,
        fetchQuote,
        createCustomer,
        createPayment,
        payment,
        productOptions,
        order,
        error,
        setError
      }}
    >
      {children}
    </MultiStepFormContext.Provider>
  );
};

export const useMultiStepForm = () => useContext(MultiStepFormContext);
