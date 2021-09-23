
# Upgrade

Any major updates which include breaking changes will be documented here.

If you are upgrading a major version, you should always refer to this document to ensure you make all necessary changes.

When upgrading, you should upgrade one version at a time.

## v5.0

All projects that uses the SDK will need to update to support at least Laravel 8 and PHP 8.0. You can find the relevant upgrade guides online.
This includes using modern class-based factories rather than the old closure-type factories. The SDK and Control now use these, so if you use the
factories directly you'll need to use `Model::factory()` rather than `factory(Model::class)`.

On the frontend, if you make use of any JavaScript injected variables such as the module, activity etc, you should make sure 
you're using the updated keys. You can find these in the developer documentation.

Support for the cookies_warning and analytics blade components has been removed, so if you use these you should move the code to the 
relevant blade partial.

Middleware classes have been standardised. The SDK normally registers module routes, therefore this will normally have been taken care of, but
if you use any portal middlewares manually make sure it's updated to the new documented groups.

We now use version 2 of the form schema generator, which has a new API. You must update any settings to match this API.

The rest of the changes are caused by the move to portal-auth - all database user authentication stuff is now abstracted, and the SDK,
and therefore all modules, should only rely on control users/groups/roles. The `BristolSU\Support\User\Contracts\UserAuthentication` class has
been removed, so if you need to make use of user authentication use `BristolSU\Support\Authentication\Contracts\Authentication` instead. You
should never interact with the database/authentication user directly, only use control. Similarly, `BristolSU\Support\User\Contracts\UserRepository` and `BristolSU\Support\User\User`
have been removed. `$request->user()` also now returns a control user rather than the old database user.

In tests, we've removed the `getDatabaseUser()`  and `setDatabaseUser()`, as these are no longer used in tests.

To make the change easier, we've added  `hasUser`, `hasGroup` and `hasRole` to the Authentication contract, which all return booleans. If you implement
this contract anywhere, you'll have to add these methods.

Finally, we've deleted the `BristolSU\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider` as these are now handled through `WebRequestActivityInstanceResolver` and `ApiActivityInstanceResolver`.
If you were using the original provider, please resolve the `BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver` class instead to get and set the activity instance.

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
