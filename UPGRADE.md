# Upgrade

Any major updates which include breaking changes will be documented here.

If you are upgrading a major version, you should always refer to this document to ensure you make all necessary changes.

When upgrading, you should upgrade one version at a time.

## [v3.0]

### Filters

If you have created any custom filters, you will need to convert the options() function to return a \FormSchema\Schema\Form class.

### Completion Conditions

If you have created any completion conditions, you must change the options() function to return a \FormSchema\Schema\Form class instead of an array.

### Module Instances
Module Instances should now be retrieved using the Module Instance Repository

### User IDs
In version 3, we introduce user IDs to the Activity, Action Instance, Logic and Module Instance models. Although this column is nullable, a null user ID will throw an exception when retrieved.

To upgrade, ensure there are no null user IDs in the four tables. We recommend running a query to convert all null columns to contain your user ID.

## [v2.0]

### Database User

If you rely on the Forename, Surname, Email or Student ID columns in the database user model, these have now been removed. You can access the same data
by getting the data user from the control model.

The Database \BristolSU\Support\User\UserRepository::getWhereIdentity function has been removed and should be replaced with getWhereEmail or getFromControlID.
