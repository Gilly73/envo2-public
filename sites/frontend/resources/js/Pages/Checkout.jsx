import { useState } from 'react';
import { useMultiStepForm } from '../context/MultiStepFormContext.jsx';
import Layout from "./Layout";
import InputField from '../components/form/Inputfield';
import { router } from '@inertiajs/react';
import FormButtons from '../components/navigation/NavButtons.jsx';
import { CardNumberElement, CardExpiryElement, CardCvcElement, CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

const Checkout = () => {

  console.log('Checkout.jsx');
  const { quote,
    updateFormData,
    createPayment,
    paymentSucceeded
  } = useMultiStepForm();

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Optionally, you can access stripe and elements here as well if needed.
  const stripe = useStripe();
  const elements = useElements();


  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const formElement = e.target;
    const submittedData = Object.fromEntries(new FormData(formElement).entries());

    updateFormData(submittedData);

    try {
      await createPayment(submittedData);
    } catch (error) {
      setError(error.message || 'Payment failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Layout>
      <div className="bg-white shadow-md rounded-lg p-6 w-full max-w-lg mx-auto">
        <h2 className="text-orange-500 text-2xl font-bold mb-4">Make Payment</h2>
        <FormButtons
          showBack={true}
          showSubmit={false}
          showReset={false}
          onBack={() => router.visit('/customer-details')}
        />
        <div>
          {paymentSucceeded ? (
            <div>Payment succeeded!</div>
          ) : (
            <form onSubmit={handleSubmit}>
              <CardElement options={{ hidePostalCode: true }}/>
             
              {error && <div style={{ color: 'red' }}>{error}</div>}
              <button id="pay" type="submit" disabled={!stripe || loading}>
                {loading ? 'Processing...' : 'Pay £' + quote.totalCost}
              </button>
            </form>
          )}
        </div>
      </div>
    </Layout>
  );
};

export default Checkout;