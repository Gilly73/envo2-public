// TestStripeProvider.jsx
import React from 'react';
import { Elements } from '@stripe/react-stripe-js';

// Create a fake Stripe instance with the method you need.
const mockStripe = {
  confirmCardPayment: () =>
    Promise.resolve({ paymentIntent: { status: 'succeeded' } }),
};

// Wrap the fake instance in a promise as expected by the Elements provider.
const stripePromise = Promise.resolve(mockStripe);

// Custom provider for testing that wraps children with Stripe Elements.
export const TestStripeProvider = ({ children }) => (
  <Elements stripe={stripePromise}>
    {children}
  </Elements>
);
