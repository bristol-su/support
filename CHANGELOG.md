# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- should_queue column to action instances
- If should_queue is false, the action is dispatched immediately

## [4.2] - (23/03/2020)

### Changed
- Action is now an abstract class not an interface
- All actions must return an ActionResponse class instance
- All actions must define a 'run' method instead of a 'handle' method

### Added
- Created action history table
- HasHistory trait for actions
- Get all history through an action instance

## [4.1] - (19/03/2020)

### Added
- UserTagged and RoleTagged filters
- \BristolSU\Support\Action\ActionInstanceRepository::forModuleInstance method
- \BristolSU\Support\Action\ActionInstanceRepository::getById method
- \BristolSU\Support\Action\ActionInstanceRepository::update method
- \BristolSU\Support\Action\ActionInstanceRepository::delete method
- \BristolSU\Support\Action\ActionInstanceRepository::all method
- \BristolSU\Support\Completion\CompletionConditionInstanceRepository::getById method
- \BristolSU\Support\Completion\CompletionConditionInstanceRepository::update method
- \BristolSU\Support\Filters\FilterInstanceRepository::getById method
- \BristolSU\Support\Filters\FilterInstanceRepository::update method
- \BristolSU\Support\Filters\FilterInstanceRepository::delete method
- \BristolSU\Support\Logic\LogicRepository::getById method
- \BristolSU\Support\Logic\LogicRepository::update method
- \BristolSU\Support\Logic\LogicRepository::delete method
- \BristolSU\Support\ModuleInstance\ModuleInstanceRepository::update method
- \BristolSU\Support\ModuleInstance\ModuleInstanceRepository::delete method

## [4.0] - (18/03/2020)

### Changed
- Action Instances, Activities, Module Instances and Logics retrieve the user ID from UserAuthentication not Authentication
- Changed event_field in action_instance_fields table to action_value
- Allow text to be entered into the action value, replace any {{event:field}} with the event 'field' value
- Added static Action::options() method to return a form schema
- ActionInstance::action_schema property

### Removed
- getFields and getFieldMetaData from Action contract
- ActionInstance::action_fields property

## [3.1.2] - (18/03/2020)

### Fixed
- Fix bug with ApiAuthentication implementation picking up a request body as a query string

## [3.1.1] - (18/03/2020)

### Added
- Middleware to throw an ActivityDisabled exception if the activity is disabled
- Middleware to throw a ModuleInstanceDisabled exception if the module instance is disabled

## [3.1] - (16/03/2020)

### Changed
- Activity Instance related middleware now only runs on participant routes

### Added
- Middleware to check the user in Authentication belongs to the database user
- Middleware to check the logged in group and role are valid
- Middleware to check the Activity Instance is owned by the logged in credentials
- Use the request query parameters to save authentication information, as opposed to the session
- Use request query parameters to get activity instance information, as opposed to the session

### Removed
- LaravelAuthActivityInstanceResolver class
- WebSessionAuthentication class
- LogIntoActivityInstance Middleware

## [3.0.2] - (11/03/2020)

### Changed
- Removed Global Scope from the relationship between a connection and a module instance service

### Removed
- Remove BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityInstanceEvaluator::evaluateAdministrator function
- Remove BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator::evaluateAdministrator activity instance parameter

## [3.0.1] - (28/02/2020)

### Added
BristolSU\Support\Testing\HandlesAuthorization to help with testing modules with permissions

## [3.0] - (22/02/2020)

### Changed
- Filter::options() should now return a \FormSchema\Schema\Form class
- Filter::toArray() converts the FormSchema to a Vue Form Generator schema
- Updated all filters supplied by the SDK
- CompletionCondition::options() should now return a \FormSchema\Schema\Form class
- ModuleBuilder calls toArray on the completion condition as opposed to casting itself.
- Activity Repository::active() now returns only activities that are enabled

### Added
- Implement CompletionCondition::toArray and ::toJson for converting completion conditions to array/json representations.
- enabled column to the activities and module_instances tables
- enabled scope to an Activity model
- enabled scope to a Module Instance model
- \BristolSU\Support\ModuleInstance\ModuleInstanceRepository::allThroughActivity() to get all module instances for an activity
- \BristolSU\Support\ModuleInstance\ModuleInstanceRepository::allEnabledThroughActivity() to get all enabled module instances for an activity
- user_id column to an Activity, Module Instance, Action Instance and Logic Group
- 'For' property to a Module model. This property is either user, group or role and allows modules to, e.g., require a group to be logged in to use the module.
- HasRevision trait for tracking model revisions in the SDK
- \BristolSU\Support\Completion\Contracts\CompletionConditionTester::evaluatePercentage method added for getting the percentage complete of a completion condition

## [2.1] - (05/02/2020)

### Added
- .gitattributes file
- \BristolSU\Support\User\UserRepository::getById method for retrieving a user by ID
- \BristolSU\Support\User\UserRepository::setRememberToken to set a users remember token
- \BristolSU\Support\User\User::getEmailForVerification to get the email to use for verification
- \BristolSU\Support\User\User::getEmailForPasswordReset to get the email to use for password resets

## [2.0.1] - (05/02/2020)

### Added
- Integration to use a user as a notifiable in the laravel framework, for mail  

## [2.0] - (04/02/2020)

### Added
- doctrine/dbal dependency for editing migrations
- Logout function for UserAuthentication
- UserRepository::getWhereEmail function to retrieve a user via email
- UserRepository::getFromControlId function to retrieve a user via control ID
- UserRepository::getFromRememberToken function to get by remember token
- Functions to get the control user from the database user

### Removed
- Forename, surname, email and student ID columns from database user model.
- UserRepository::getWhereIdentity function

## [1.1] - (30/01/2020)

### Changed
- Bumped the control dependency to version 1 (stable)
- Allow Module Instance Setting values to be null
- Refresh the module builder instance in the factory

### Added
- Laravel Session Authentication

### Removed
- Laravel Web Authentication



## [1.0.1] - (23/01/2020)

### Changed
- Generate a new module builder each time the module factory is used
- Make module instance setting values nullable 
- Connection ID attribute is now fillable for a module instance service
- Prefer stable dependencies

## [1.0] - (17/01/2020)

### Added
- Initial Release

[Unreleased]: https://github.com/bristol-su/support/compare/v4.2...HEAD
[4.2]: https://github.com/bristol-su/support-compare/v4.1...v4.2
[4.1]: https://github.com/bristol-su/support-compare/v4.0...v4.1
[4.0]: https://github.com/bristol-su/support/compare/v3.1.1...v4.0
[3.1.1]: https://github.com/bristol-su/support/compare/v3.1...v3.1.1
[3.1]: https://github.com/bristol-su/support/compare/v3.0.2...v3.1
[3.0.2]: https://github.com/bristol-su/support/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/bristol-su/support/compare/v3.0...v3.0.1
[3.0]: https://github.com/bristol-su/support/compare/v2.1...v3.0
[2.1]: https://github.com/bristol-su/support/compare/v2.0.1...v2.1
[2.0.1]: https://github.com/bristol-su/support/compare/v2.0...v2.0.1
[2.0]: https://github.com/bristol-su/support/compare/v1.1...v2.0
[1.1]: https://github.com/bristol-su/support/compare/v1.0.1...v1.1
[1.0.1]: https://github.com/bristol-su/support/compare/v1.0...v1.0.1
[1.0]: https://github.com/bristol-su/support/releases/tag/v1.0
