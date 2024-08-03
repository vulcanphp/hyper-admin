<?php

namespace admin\views;

use hyper\request;
use hyper\utils\form;
use admin\admin;
use admin\core\modelView;

class bread
{
    function browse(request $request, string $model)
    {
        $model = admin::$instance->getModel($model);
        return admin::$instance->template('bread/browse', ['model' => $model]);
    }

    function add(request $request, string $model)
    {
        $model = admin::$instance->getModel($model);
        $form = new form(request: $request, model: $model->getModel());
        if ($request->method === 'POST') {
            return $this->saveForm($form, $request, $model, 'added');
        }
        return admin::$instance->template('bread/add', ['model' => $model, 'form' => $form]);
    }

    function change(request $request, string $model, int $id)
    {
        $model = admin::$instance->getModel($model);
        $object = $model->getModel()->find($id);
        if (!$object) {
            session()->set('warning', str_replace('##', $id, __('There is no ' . $model->name() . ' with id ##', true)));
            return redirect(admin_prefix('model/' . $model->name()));
        }
        $form = new form(request: $request, model: $object);
        if ($request->method === 'POST') {
            return $this->saveForm($form, $request, $model, 'changed');
        }
        return admin::$instance->template('bread/change', ['model' => $model, 'object' => $object, 'form' => $form]);
    }

    function delete(request $request, string $model)
    {
        $is_ajax = $request->accept('application/json');
        $model = admin::$instance->getModel($model);
        $objects = $model->getModel()->where(['id' => explode(',', $request->post('deleteConfirmed', $request->post('ids', '')))])->result();
        if (!empty($objects) && !empty($request->post('deleteConfirmed'))) {
            $deleted = [];
            foreach ($objects as $object) {
                // delete related/child objects
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
                // delete current object
                $deleted[] = $object->remove();
            }
            if (!in_array(false, $deleted)) {
                session()->set('success', str_replace('##', count($deleted), __('Successfully deleted ## ' . $model->name_plural() . '.', true)));
            } else {
                session()->set('warning', __('Failed to delete all selected ' . $model->name_plural() . '.', true));
            }
            $redirect = admin_prefix('model/' . $model->name());
            return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
        }
        if (empty($objects)) {
            session()->set('warning', __('There is no ' . $model->name_plural() . ' available to delete.', true));
            $redirect = admin_prefix('model/' . $model->name());
            return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
        }
        return admin::$instance->template('bread/delete', ['model' => $model, 'objects' => $objects]);
    }

    /** Private Functions for Bread..*/
    private function saveForm(form $form, request $request, modelView $model, string $action)
    {
        $is_ajax = $request->accept('application/json');
        if ($form->validate() && $form->save()) {
            $saved = $form->getModel();
            $message = str_replace('##', '“<a fire class="text-cyan-400" href="' . admin_url('model/' . $model->name() . '/' . $saved->id . '/change') . '">' . $saved . '</a>”', __('The ' . $model->name() . ' ## was ' . $action . ' successfully.', true));
            if (!empty($request->post('_save_add'))) {
                session()->set('success', $message . __(' You may add another ' . $model->name() . ' below.', true));
                $redirect = admin_prefix('model/' . $model->name() . '/add');
                return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
            } elseif (!empty($request->post('_save_edit'))) {
                session()->set('success', $message . __(' You may edit it again below.', true));
                $redirect = admin_prefix('model/' . $model->name() . '/' . $saved->id . '/change');
                return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
            } else {
                session()->set('success', $message);
            }
        } else {
            session()->set('error', __('Failed to save ' . $model->name(), true));
        }
        $redirect = admin_prefix('model/' . $model->name());
        return $is_ajax ? response()->json(['push' => $redirect]) : redirect($redirect);
    }
}
