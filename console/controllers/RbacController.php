<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        // add "manageCatalogFlex"
        $manageCatalogFlex = $auth->createPermission('manageCatalogFlex');
        $manageCatalogFlex->description='Manage Catalog Flex Values';
        $auth->add($manageCatalogFlex);

        // add "createPost" permission
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);

        // add the rule
        $rule = new \common\rbac\AuthorRule;
        $auth->add($rule);

        // add the "updateOwnPost" permission and associate the rule with it.
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($updateOwnPost, $updatePost);

        // allow "author" to update their own posts
        $auth->addChild($author, $updateOwnPost);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);
        $auth->addChild($admin, $manageCatalogFlex);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 2);
        $auth->assign($admin, 1);
    }

    public function actionCatalogInit() {
        $auth = Yii::$app->authManager;

    }
}