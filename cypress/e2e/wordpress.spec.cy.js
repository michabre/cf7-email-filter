/// <reference types="cypress" />

describe('WordPress Site', () => {
  it('displays the website title', () => {
  cy.visit('http://wordpress-plugin-dev-3.lndo.site/');
  cy.contains('WordPress Plugin Dev 3');
  });
});

describe('Test a CF7 Form', () => {
  it('Verify the form exists', () => {
    cy.visit('http://wordpress-plugin-dev-3.lndo.site/contact-form-7-test/');
    cy.get('.wpcf7-form input[name="your-name"]').should('exist');
    cy.get('.wpcf7-form input[name="your-email"]').should('exist');
    cy.get('.wpcf7-form input[name="your-subject"]').should('exist');
    cy.get('.wpcf7-form textarea[name="your-message"]').should('exist');
  });
});

describe('Complete CF7 Form with Good Email', () => {
  it('Verify form submission with all fields filled correctly', () => {
    cy.visit('http://wordpress-plugin-dev-3.lndo.site/contact-form-7-test/');
    cy.get('.wpcf7-form input[name="your-name"]').type('Test');
    cy.get('.wpcf7-form input[name="your-email"]').type('test@test.com');
    cy.get('.wpcf7-form input[name="your-subject"]').type('Test');
    cy.get('.wpcf7-form textarea[name="your-message"]').type('This is a test');
    cy.get('.wpcf7-form input[type="submit"]').click();

    cy.get('.wpcf7-response-output').should('exist');
    cy.get('.wpcf7-response-output').contains('Thank you for your message. It has been sent.');
  });
});

describe('Complete CF7 Form with Bad Email', () => {
  it('Verify warning displayed for disallowed email address', () => {
    cy.visit('http://wordpress-plugin-dev-3.lndo.site/contact-form-7-test/');
    cy.get('.wpcf7-form input[name="your-name"]').type('Test');
    cy.get('.wpcf7-form input[name="your-email"]').type('test@hotmail.com');
    cy.get('.wpcf7-form input[name="your-subject"]').type('Test');
    cy.get('.wpcf7-form textarea[name="your-message"]').type('This is a test');
    cy.get('.wpcf7-form input[type="submit"]').click();

    cy.get('span[data-name="your-email"] .wpcf7-not-valid-tip').should('exist');
    cy.get('span[data-name="your-email"] .wpcf7-not-valid-tip').contains('Please input a valid business email.');
  });
});