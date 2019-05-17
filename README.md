# Refactoring task

## Description
There is a `ZendeskService` class which is part of is part of a Zend Framework 2 project.

Refactor the given `ZendeskService` class.

> Give a list of prioritised issues that need to be refactored from the most important to the less valuable from an OOP perspective.

Try to apply best PHP and **OOP** practices.

## Solution

There are two possible scenarios when refactoring a class:

- It is not possible to make changes on the places where the class is used: This means that the input and output of 
the class constructor and the other methods should remain unchanged. This gives some limitations on what can be refactor as the input and output might need some refactoring.
- It is possible  to make changes on the places where the class is used. This gives more freedom for refactoring and achieving better overall result. 

No matter what the case is, it is important to remember that the functionality must remain the same. 
Optimizing the functionality is not part of a refactoring task.

**The given solution works with the first scenario where It is not possible to make changes on the places where the class is used.**

###The following is a list of prioritized issues that are refactored:
- removing repetition - making a method that do the job and call it (example: make createTicket method; move the client authentication in the constructor as it's needed in each method)
- improving readability (example: moving the ticket custom fields codes to a mapping array).
- simplifying the logic where it's possible (KISS principle)(example: refactor multiple nested ifs )
- applying separation of concerns (example: making TicketFieldsBuilder class). It is important a single class / method to have a single purpose.
- using dependency injection instead making instances in multiple places the class
- using DTOs instead of passing a lot parameters
- remove unused things( variables and constants etc... )

All of there things make the code more maintainable, less prone to errors and easier to extend.