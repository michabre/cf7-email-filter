/// <reference types="cypress" />

describe('WordPress Site', () => {
  it('displays the application title', () => {
  cy.visit('http://wordpress-plugin-dev-3.lndo.site/');
  cy.contains('WordPress Plugin Dev 3');
  });
});