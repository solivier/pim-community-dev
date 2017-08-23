@javascript
Feature: Filter product and product models
  In order to filter and show product and product models in the same grid
  As a regular user
  I need to be able to show and filter products and product models in the catalog

  Background:
    Given the "catalog_modeling" catalog configuration
    And I am logged in as "Mary"

  Scenario: Successfully filter and display both products and product models
    Given I am on the products page
    When I show the filter "color"
    And I filter by "color" with operator "in list" and value "Crimson red"
    Then I should see products tshirt-unique-size-crimson-red, running-shoes-xxs-crimson-red, running-shoes-m-crimson-red, running-shoes-xxxl-crimson-red
    And I should see the product models model-tshirt-divided-crimson-red, model-tshirt-unique-color-kurt
    And the row "model-tshirt-divided-crimson-red" should contain:
      | column           | value                            |
      | SKU              | model-tshirt-divided-crimson-red |
      | label            | Divided crimson red              |
      | family           | Clothing                         |
      | Status           |                                  |
      | complete         | N/A                              |
      | groups           |                                  |
      | variant products |                                  |
    And the row "running-shoes-xxs-crimson-red" should contain:
      | column           | value                         |
      | SKU              | running-shoes-xxs-crimson-red |
      | label            | running-shoes-xxs-crimson-red |
      | family           | Shoes                         |
      | Status           | Enabled                       |
      | complete         | 25%                           |
      | groups           |                               |
      | variant products |                               |
