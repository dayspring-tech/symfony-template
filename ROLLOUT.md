# Preparing to roll out to Stage

When a section of functionality is completed the changes should be staged so that the client
can review and approve the changes made before being put in production.

## Step 1: Create the release branch

Make sure your develop branch is at the correct commit, and then create a new relase branch using
git-flow.  The bransh should be names with the current date. For example, release/20160212.

Push the new release branch to github/bitbucket.

## Step 2: Deploy the release branch to stage

Open the stage Opsworks stack and change the branch that the app pulls from.
- Set the `Branch/Revision` to the release branch you created.
- Run a deploy of the app.


# Preparing to roll out to Production

After sign off is received from the client we are ready to move the functionality
to production.

## Step 1: Create a Pull Request to merge release branch into master

Make sure that master is up-to-date.  Create Pull Request and include the template
below into the description.

```md
| Q                                   | A
| ----------------------------------- | ---
| Has been staged?                    | yes|no
| Previous production commit          | commit_sha
| Client sign-off?                    | yes|no
| Database migrations?                | yes|no
| Current database version            | propel_migration_version

## Rollback instructions

- Rollback database migrations (if any, and not backwards compatible)
    - Log into server
    - `cd /srv/www/[app name]/current/symfony`
    - Run `php app/console propel:migration:migrate --down` until you reach the
    database version from the table above
- Deploy previous version of the app
    - Open the production Opsworks stack
    - Set the `Branch/Revision` of the app to the commit_sha from the table above.
    - Deploy the app

```

Fill in the template with the correct answers.
The commit_sha is a 40 character id of the latest version of the branch currently
deployed. Check the Opsworks application settings to find the current branch, then
check github/bitbucket for the latest revision.
The propel_migration_version can be found by checking the `propel_migration` table
of the database.

Make sure to add steps that may be required to back out this particular rollout.

## Step 2: Merge the PR into master

Use the tools in github/bitbucket to merge the Pull Request into Master.

*Generally we do not delete the release branches after they are merged. But even
if we are in this case it should be done after step 5 below.*

## Step 3: Pull into stage and validate

Now pull master into **stage** so that the code can be validated once more.

Open the stage Opsworks stack and change the branch that the app pulls from.
- Set the `Branch/Revision` to `master`.
- Run a deploy of the app.

If additional things need to be done for this particular rollout test them here and
add to the rollback instructions in the PR accordingly.

## Step 4: Pull changes into production

Now pull master into the **production** server.

Open the production Opsworks stack and change the branch that the app pulls from.
- Set the `Branch/Revision` to `master`.
- Run a deploy of the app.

If more things were found to be done in Step 3 do them here for production too.

## Step 5: Merge release branch into develop

This can be done in SourceTree locally or in github/bitbucket.
