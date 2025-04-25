// PaymentFlow.spec.jsx
import React from 'react';
import { TestStripeProvider } from './TestStripeProvider';
import { MultiStepFormProvider } from "../../resources/js/context/MultiStepFormContext.jsx";
import PaymentFlowTester from './PaymentFlowTester';


describe('Couch Quote Flow', () => {
  beforeEach(() => {
    // Intercept the API call for payment creation
    cy.intercept('POST', '/customer/payment', {
      statusCode: 200,
      body: {
        clientSecret: 'pi_1234_secret_abcd'
      }
    }).as('fakePaymentIntent');
  });

  it('should complete the couch quote process and simulate a successful payment', () => {
    // 1. Fill out the quote form on your frontend
    cy.visit('https://frontend');
    cy.get('[name="couchtype"]').should('be.visible').select('Indoor');
    cy.get('[name="fabrictype"]').select('Velvet');
    cy.get('[name="legtype"]').select('Wood');
    cy.get('[name="seatertype"]').select('2');
    cy.get('[name="discount"]').type('ABC123');
    cy.get('[name="country"]').select('UK');
    cy.get('[id="quote"]').click();

    // 3. Assert that the quote total cost is displayed
    cy.contains('Quote Total Cost: £2664.00');

    // 4. Fill in personal details on the next page
    cy.get('[name="firstName"]').should('be.visible').type('John');
    cy.get('[name="surname"]').type('Doe');
    cy.get('[name="dob"]').type('1985-01-01');
    cy.get('[name="email"]').type('john.doe@example.com');
    cy.get('[name="mobile"]').type('07900000000');
    cy.get('[name="address1"]').type('line 1');
    cy.get('[name="city"]').type('city');
    cy.get('[name="county"]').type('county');
    cy.get('[name="postcode"]').type('postcode');
    cy.get('[name="country"]').type('country');

    // --- Step 2: Stub Stripe's confirmCardPayment ---
    // Wait until the app has created its Stripe instance.
    cy.mount(
      <TestStripeProvider>
        <MultiStepFormProvider>
          <PaymentFlowTester />
        </MultiStepFormProvider>
      </TestStripeProvider>
    );

    // 5. Proceed to the customer step
    cy.get('[id="customer"]').click();

    cy.wait('@fakePaymentIntent');

    // 6. Verify that the payment page displays the correct total
    cy.contains('Pay £2664.00');

  
  });
});
