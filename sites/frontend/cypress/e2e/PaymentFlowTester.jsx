// PaymentFlowTester.jsx
import React from 'react';
import { useMultiStepForm } from "../../resources/js/context/MultiStepFormContext.jsx";

const PaymentFlowTester = () => {
  const { createPayment, paymentSucceeded } = useMultiStepForm();

  const handlePayment = async () => {
    try {
      // You can pass any required checkout form data here; for simplicity we pass an empty object.
      await createPayment({});
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <div>
      <button onClick={handlePayment}>Pay Now</button>
      {paymentSucceeded && <div>Payment successful</div>}
    </div>
  );
};

export default PaymentFlowTester;
