<?php

namespace admin\views;

use hyper\request;
use hyper\utils\form;
use admin\admin;
use admin\core\modelView;

/**
 * views bread
 * 
 * Contains functions to handle the bread actions:
 * - browse: displays a list of items in a table
 * - add: shows a form to add a new item of the given model
 * - change: shows a form to change an existing item of the given model
 * - delete: deletes an item of the given model
 * 
 * @package admin
 * @author Shahin Moyshan <Shahin.moyshan2@gmail.com>
 * @since 1.0.0
 */
class bread
{
    /**
     * Display a list of items in a table.
     * This function is called when the user wants to see a list of items.
     * 
     * 
     * @param string $model The name of the model to list
     * @return \hyper\response the response object
     */
    function browse($model)
    {
        $model = admin::$instance->getModel($model);
        return admin::$instance->template('bread/browse', ['model' => $model]);
    }

    /**
     * Show a form to add a new item of the given model.
     * This function is called when the user wants to add a new item.
     * It will display a form with the fields of the model, and when the
     * form is submitted, it will save the new item in the database.
     * 
     * @param request $request The current HTTP request
     * @param string $model The name of the model to add
     * @return \hyper\response
     */
    function add(request $request, string $model)
    {
        $model = admin::$instance->getModel($model);
        $form = new form(request: $request, model: $model->getModel());

        if ($request->method === 'POST') {
            return $this->saveForm($form, $request, $model, 'added');
        }

        return admin::$instance->template('bread/add', ['model' => $model, 'form' => $form]);
    }

    /**
     * Show a form to change an existing item of the given model.
     * This function is called when the user wants to edit an existing item.
     * It will display a form with the fields of the item, and when the form is submitted,
     * it will save the changes to the item in the database.
     * 
     * @param request $request The current HTTP request
     * @param string $model The name of the model to change
     * @param int $id The ID of the item to change
     * @return \hyper\response
     */
    function change(request $request, string $model, int $id)
    {
        $model = admin::$instance->getModel($model);
        $object = $model->getModel()->find($id);

        if (!$object) {
            session()->set('warning', str_replace('##', $id, __('There is no ' . $model->name() . ' with id ##', true)));
            return redirect(admin_prefix('model/' . $model->name()));
        }

        // Create a form with the fields of the model.
        $form = new form(request: $request, model: $object);

        if ($request->method === 'POST') {
            // Save the changes to the form/model.
            return $this->saveForm($form, $request, $model, 'changed');
        }

        // Display the form with the fields of the item
        return admin::$instance->template('bread/change', [
            'model' => $model,
            'object' => $object,
            'form' => $form
        ]);
    }

    /**
     * Delete one or more items of the given model.
     * This function is called when the user wants to delete items.
     * It handles both AJAX and normal requests, removing the specified items 
     * and their related/child objects from the database.
     * 
     * @param request $request The current HTTP request
     * @param string $model The name of the model to delete items from
     * @return \hyper\response the response object
     */
    function delete(request $request, string $model)
    {
        // Get the model and check if the request is AJAX.
        $is_ajax = $request->accept('application/json');
        $model = admin::$instance->getModel($model);

        // Get the objects from databse to delete.
        $objects = $model->getModel()
            ->where([
                'id' => explode(
                    ',',
                    $request->post('deleteConfirmed', $request->post('ids', ''))
                )
            ])->result();

        if (!empty($objects) && !empty($request->post('deleteConfirmed'))) {
            $deleted = [];
            foreach ($objects as $object) {
                // delete related/child objects related using orm.
                if (method_exists($object, 'getRegisteredOrm')) {
                    foreach ($object->getRegisteredOrm() as $with => $rel) {
                        if (!in_array($rel['has'], ['many'])) {
                            continue;
                        }
                        foreach ($object->{$with} as $relObj) {
                            $relObj->remove();
                        }
                    }
                }

                // delete current object.
                $deleted[] = $object->remove();
            }

            // Set the success or warning message into the session.
            if (!in_array(false, $deleted)) {
                session()->set(
                    'success',
                    str_replace(
                        '##',
                        count($deleted),
                        __('Successfully deleted ## ' . $model->name_plural() . '.', true)
                    )
                );
            } else {
                session()->set(
                    'warning',
                    __('Failed to delete all selected ' . $model->name_plural() . '.', true)
                );
            }

            // Redirect to the model index page.
            $redirect = admin_prefix('model/' . $model->name());
            return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
        }

        // If there are no items to delete, redirect to the model index page.
        if (empty($objects)) {
            session()->set('warning', __('There is no ' . $model->name_plural() . ' available to delete.', true));
            $redirect = admin_prefix('model/' . $model->name());

            return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
        }

        // Display the form with the fields of the item.
        return admin::$instance->template('bread/delete', ['model' => $model, 'objects' => $objects]);
    }

    /** @Add private functions for bread..*/

    /**
     * Handles the form submission and saving of the form model
     *
     * @param form $form
     * @param request $request
     * @param modelView $model
     * @param string $action
     *
     * @return \hyper\response
     */
    private function saveForm(form $form, request $request, modelView $model, string $action)
    {
        // Check the request is AJAX.
        $is_ajax = $request->accept('application/json');

        // Save the changes to the form/model.
        if ($form->validate() && $form->save()) {
            // Set the new record id.
            $saved = $form->getModel();

            // Create a message to display.
            $message = str_replace(
                '##',
                '“<a fire class="text-cyan-400" href="' . admin_url('model/' . $model->name() . '/' . $saved->id . '/change') . '">' . $saved . '</a>”',
                __('The ' . $model->name() . ' ## was ' . $action . ' successfully.', true)
            );

            // Set the success or warning message into the session.
            if (!empty($request->post('_save_add'))) {
                session()->set(
                    'success',
                    $message . __(' You may add another ' . $model->name() . ' below.', true)
                );

                // Return to the page to create a new record.
                $redirect = admin_prefix('model/' . $model->name() . '/add');
                return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
            } elseif (!empty($request->post('_save_edit'))) {
                session()->set(
                    'success',
                    $message . __(' You may edit it again below.', true)
                );

                // Return to the edit page again.
                $redirect = admin_prefix('model/' . $model->name() . '/' . $saved->id . '/change');
                return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
            } else {
                session()->set('success', $message);
            }
        } else {
            session()->set(
                'error',
                __('Failed to save ' . $model->name(), true)
            );
        }

        // Redirect to the model index page.
        $redirect = admin_prefix('model/' . $model->name());
        return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
    }
}
