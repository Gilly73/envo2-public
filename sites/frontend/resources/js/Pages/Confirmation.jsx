import { useState } from 'react';
import { useMultiStepForm } from '../context/MultiStepFormContext.jsx';
import Layout from "./Layout";

const Confirmation = () => {

  const {
    quote,
    customer,
    formData,
    updateFormData,
    resetFormData,
    fetchQuote,
    createCustomer,
    payment,
    order,
    error } = useMultiStepForm();

  const [errors, setErrors] = useState({});

  return (
    <Layout>
      <div className="bg-white shadow-md rounded-lg p-6 w-full max-w-lg mx-auto">
        {error ? (
          <div className="text-red-500 mb-4">{error}</div>
        ) : order ? (
          <>
            <h2 className="text-orange-500 text-2xl font-bold mb-4">Confirmation</h2>
            <h3>Thank you - Order Details:</h3>
            <ul>
              <li>
                Order Number - {order.order_number}
              </li>
              <li>
                Description - {order.description}
              </li>
              <li>
                Total Cost - {order.currency}{order.total_cost}
              </li>
              <li>
                Discount - {order.currency}{order.discount}
              </li>

            </ul>
          </>
        ) : (
          <p>Loading...</p>
        )}
      </div>
    </Layout>
  );
};

export default Confirmation;