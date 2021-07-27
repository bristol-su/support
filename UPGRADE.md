
# Upgrade

Any major updates which include breaking changes will be documented here.

If you are upgrading a major version, you should always refer to this document to ensure you make all necessary changes.

When upgrading, you should upgrade one version at a time.

## Unreleased

- Anything from laravel 6->7 or 7->8, and php 8 needed
- Changed the variable names to be injected for JavaScript use, and do so through a view composer for all Bristol SU bases
- Add `hasUser`, `hasGroup` and `hasRole` to the Authentication contract.
- `BristolSU\Support\User\Contracts\UserAuthentication` should no longer be used, instead use Authentication and control directly. Same with
  `BristolSU\Support\User\Contracts\UserRepository`, `BristolSU\Support\User\User`.
- Removed `getDatabaseUser()` and `setDatabaseUser()` from testing - tests no longer use database users
- Deleted `BristolSU\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider` as now handled through `WebRequestActivityInstanceResolver` and `ApiActivityInstanceResolver`
- $request->user() now returns a control user
- Middleware classes have been standardised
- Removed support for the blade partials cookies_warning and analytics

## v4.0

Version 4 reformats the Action framework to be much more flexible. If you have created any actions, the options() function
must be implemented to return a \FormSchema\Schema\Form class, and the getFields and getFieldMetaData functions may be removed.

Additionally, the ActionInstance class no longer contains an action_fields property. It instead contains an action_schema
property which will return a transformed FormSchema as an array for use with a form generator.

## v3.0

### Filters

If you have created any custom filters, you will need to convert the options() function to return a \FormSchema\Schema\Form class.

### Completion Conditions

If you have created any completion conditions, you must change the options() function to return a \FormSchema\Schema\Form class instead of an array.

### Module Instances
Module Instances should now be retrieved using the Module Instance Repository

### User IDs
In version 3, we introduce user IDs to the Activity, Action Instance, Logic and Module Instance models. Although this column is nullable, a null user ID will throw an exception when retrieved.

To upgrade, ensure there are no null user IDs in the four tables. We recommend running a query to convert all null columns to contain your user ID.

## v2.0

### Database User

If you rely on the Forename, Surname, Email or Student ID columns in the database user model, these have now been removed. You can access the same data
by getting the data user from the control model.

The Database \BristolSU\Support\User\UserRepository::getWhereIdentity function has been removed and should be replaced with getWhereEmail or getFromControlID.
