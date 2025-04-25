
describe('Couch Quote and Details Flow', () => {
  it('should complete the couch quote and details process', () => {
    // 1. Visit your React app (adjust the port or path as needed)
    cy.visit('https://frontend');

    // 2. Select couch type
    // If your select has a data-cy or id/class, use that.
    // Example:
    cy.get('[name="couchtype"]').should('be.visible').select('Indoor');
    
    // 3. Select fabric type
    cy.get('[name="fabrictype"]').select('Velvet');

    // 4. Select leg type
    cy.get('[name="legtype"]').select('Wood');

    // 5. Select seater type
    cy.get('[name="seatertype"]').select('2');

    // 6. Optional: apply discount or select country
    cy.get('[name="discount"]').type('ABC123');
    cy.get('[name="country"]').select('UK');

    // 7. Click "Get Quote"
    cy.get('[id="quote"]').click();

    // 8. Assert we see the quote total cost
    cy.contains('Quote Total Cost: £2664.00');

    // 9. Fill in personal details on next page
    cy.get('[name="firstName"]').should('be.visible').type('John');
    cy.get('[name="surname"]').type('Doe');
    cy.get('[name="dob"]').type('1985-01-01');
    cy.get('[name="email"]').type('john.doe@gmail.com');
    cy.get('[name="mobile"]').type('07900000000');
    cy.get('[name="address1"]').type('line 1');
    //cy.get('[name="address2"]').type(null);
    cy.get('[name="city"]').type('city');
    cy.get('[name="county"]').type('county');
    cy.get('[name="postcode"]').type('postcode');
    cy.get('[name="country"]').type('country');

    // 7. Click "customer"
    cy.get('[id="customer"]').click();

    //10. Proceed to the payment page and confirm total
    cy.contains('Pay £2664.00');

  });
});
