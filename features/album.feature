Feature: I want to add, delete,
  edit and preview albums in database

  Scenario: I see added albums on the frontpage
    Given I want to open the frontpage
    When I visit the frontpage
    Then The page displays
    And I see table with albums

  Scenario Outline: I open edit form of an album
    Given I visit the frontpage
    When I click edit link of album ID <id>
    Then I visit edit page of album ID <id>
    And The page displays
    And The form contains title field with <title> value
    And The form contains artist field with <artist> value

    Examples:
    | id |      title      |      artist          |
    | 1  | "In My Dreams"  | "The Military Wives" |
    | 2  | "21"            | "Adele"              |

  Scenario Outline: I add new album
    Given I visit the add new album page
    When I input <title> into title field
    And I input <artist> into artist field
    And I click "Add" button
    Then I visit the frontpage
    And The page displays
    And New position with artist: <artist>, title: <title> is present on the list

    Examples:

    | title              | artist  |
    | "Behat Test Title" | "Behat Test Artist" |


