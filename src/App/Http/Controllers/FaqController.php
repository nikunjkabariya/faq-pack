<?php

namespace Nikunjkabariya\Faq;

use Nikunjkabariya\Faq\Controller;
use Nikunjkabariya\Faq\Faq as FaqModel;
use Nikunjkabariya\Faq\FaqTopic as FaqTopicModel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

class FaqController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->user = app('auth')->guard()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //$faqs = FaqTopicModel::with('faqs')->get();
        $faqs = FaqModel::with('faqTopic')->orderBy('faqs.id', 'desc')->get();
        return $faqs->isEmpty() ? $this->success(null, 'NO_RECORD_FOUND') : $this->success($faqs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validations = config('faq.validation');
        $validation = Validator::make($request->all(), $validations);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            FaqModel::createRecord($request->all());
            return $this->success(null, 'FAQ_CREATED', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Name  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $faq = FaqModel::find($id);
        return (!$faq) ? $this->error('FAQ_DOESNT_EXIST', 404) : $this->success($faq);
    }

    /**
     * Update the specified resource in storage
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id) {

        try {
            FaqModel::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return $this->error('FAQ_DOESNT_EXIST', 404);
        }

        $validations = config('faq.validation');
        $validation = Validator::make($request->all(), $validations);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        FaqModel::updateRecord($request->all(), $id);
        return $this->success(null, 'FAQ_UPDATED', 200);
    }

    /**
     * Remove the specified resource from storage
     * @param type $id
     * @return type
     */
    public function destroy($id) {
        try {
            $faq = FaqModel::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            //\Illuminate\Database\QueryException
            return $this->error('FAQ_DOESNT_EXIST', 404);
        }

        $faq->delete();
        return $this->success(null, 'FAQ_DELETED', 200);
    }

    /**
     * Get all FAQ topics
     * @return type
     */
    public function faqTopicList() {
        $faqTopics = FaqTopicModel::get();
        return $faqTopics->isEmpty() ? $this->success(null, 'NO_RECORD_FOUND') : $this->success($faqTopics);
    }

    /**
     * Get all FAQs list by faq topic slug
     * @param type $faqTopicSlug
     * @return type
     */
    public function getAllFaqsByFaqTopic($faqTopicSlug) {
        $faqs = FaqTopicModel::with([
                    'faqs' => function($query) {
                        $query->where(['status' => 'Active']);
                    }
                ])
                ->where('faq_topics.slug', $faqTopicSlug)
                ->first();

        if (!$faqs) {
            return $this->error('FAQ_TOPIC_DOESNT_EXIST');
        } else {
            return $faqs->faqs->isEmpty() ? $this->success(null, 'NO_FAQ_FOUND') : $this->success($faqs->faqs);
        }
    }

    /**
     * Change status
     * @param Request $request
     * @return type
     */
    public function changeStatus(Request $request) {

        $validation = Validator::make($request->all(), [
                    'status' => 'required',
                    'id' => 'required|integer'
        ]);

        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            $faq = FaqModel::findOrFail($request->get('id'));
            $faq->status = $request->get('status');
            $faq->save();
            return $this->success(null, ($request->get('status') == 'Active' ? 'FAQ_STATUS_ACTIVE' : 'FAQ_STATUS_INACTIVE'), 200);
        } catch (ModelNotFoundException $ex) {
            return $this->error('FAQ_DOESNT_EXIST', 404);
        }
    }

}
