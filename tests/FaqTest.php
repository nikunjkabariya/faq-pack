<?php

use Nikunjkabariya\Faq\Faq;
use Nikunjkabariya\Faq\FaqTopic;
use Carbon\Carbon;

class FaqTest extends TestCase
{
    public $header;
    
    /**
     *@test
     */
    public function setUp()
    {   
        parent::setUp();
        
        $this->header = ['Accept' => 'application/json'];
        $response = $this->call('POST', 'api/v1/oauth/signin/', [
            'email'  => 'nikunjkumar.kabariya@brainvire.com',
            'password'  => 'admin1234',
            'user_type'  => 'ADMIN'
            ], [], [], $this->header);
        
        if($response->status() == 200){
            $token  = json_decode($response->getContent())->data->access_token;
            $this->header['HTTP_Authorization'] = 'Bearer '.$token;
        }
        $this->assertEquals(200, $response->status());
    }
    
    /**
     * Test to Faq List
     */
    public function testFaqList()
    {

        //get faq and its gives error like UNAUTHORIZED
        $headers['HTTP_Authorization'] = 'Bearer fdssdf';
        $response = $this->get('api/faq/list/', [],$headers)
            ->seeJson([
                'status_code' => 401
             ]);

        $response = $this->call('GET', 'api/faq/list/', [
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test create FAQ
     */
    public function testUserCreate()
    {

        //get validation error 
        $response = $this->post('api/faq/create', [
            'question'  => 'what is profit scenario ?',
        ],$this->header)
            ->seeJson([
                'status_code' => 422
             ]);
        
        //create user
        $response = $this->call('POST', 'api/faq/create', [
            'faq_topic_id'  => 1,
            'question'  => "what is profit scenario ?",
            'answer'  => "ANSWER",
            'status'  => "Active",
            ], [], [], $this->header);
        
        $this->assertEquals(201, $response->status());
    }
    
    /**
     * Test update faq
     */
    public function testUserUpdate()
    {
        $faq = Faq::where('answer', 'ANSWER')->first();
        //get validation error 
        $response = $this->put('api/faq/update/'.$faq->id, [
            'question'  => 'nimish',
        ],$this->header)
            ->seeJson([
                'status_code' => 422
             ]);
        
        //update faq
        $response = $this->call('PUT', 'api/faq/update/'.$faq->id, [
            'faq_topic_id'  => 1,
            'question'  => "what is profit scenario ?",
            'answer'  => "ANSWER",
            'status'  => "Active",
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
        
    }
    
    /**
     * Test faq status
     */
    public function testFaqStatus()
    {
        $faq = Faq::where('answer', 'ANSWER')->first();
        //get status update 
        $response = $this->put('api/faq/change_status/', [
            'status'  => 'Active',
            'id'  => $faq->id
        ],$this->header)
            ->seeJson([
                'status_code' => 200
             ]);
    }
    
    /**
     * Show faq
     */
    public function testUserShow()
    {
        $faq = Faq::where('answer', 'ANSWER')->first();
        
        $response = $this->call('GET', 'api/faq/show/'.$faq->id, [
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Delete faq
     */
    public function testFaqDelete()
    {
        $faq = Faq::where('answer', 'ANSWER')->first();
        
        $response = $this->delete('api/faq/delete/'.$faq->id, [
        ],$this->header)
            ->seeJson([
                'status_code' => 200
             ]);
        //delete created $faq
        $faq->delete();
        
    }

    /**
     * Show faq topic list
     */
    public function testFaqTopicList()
    {
        $response = $this->call('GET', 'api/faq_topic/list/', [
            ], [], [], $this->header);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Show faq list by slug
     */
    public function testFaqListBySlug()
    {   
        $faqTopic['slug'] = 'test';
        $faqTopic['topic_name'] = 'test';
        $faqTopic['status'] = 'Active';
        $faqTopic['created_at'] = Carbon::now();
        $faqTopics = FaqTopic::create($faqTopic); 
        $response = $this->call('GET', 'api/faqs/'.$faqTopics->slug, [
            ], [], [], $this->header);
        $faqTopics->delete();
        $this->assertEquals(200, $response->status());
    }
    
}
