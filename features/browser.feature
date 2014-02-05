Feature: Browser manipulation

    Scenario: I can go to a given page
        Given I am on "/"
         Then I should be on "/"
        Given I am on "/rand.php"
         Then I should be on "/rand.php"

    Scenario: I can refresh page
        Given I am on "/rand.php"
          And I remember text
         When I refresh
         Then I should see a text different from remembered

    Scenario: I can test URL
        Given I am on "/"
          And I click on "Another page"
         Then I should be on "/other.php"

    Scenario: I can delete a cookie
        Given I am on "/cookies.php"
          And I click on "Create foo cookie"
         Then I should see "value of the foo cookie"
         When I delete cookie "foo"
          And I refresh
         Then I should not see "value of the foo cookie"
