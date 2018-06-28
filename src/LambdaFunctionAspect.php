<?php

namespace imonroe\cr_basic_aspects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\AspectFactory;
use imonroe\crps\Subject;
use imonroe\ana\Ana;
use Validator;

class LambdaFunctionAspect extends Aspect{

    function __construct()
    {
		parent::__construct();
	}

    public function notes_schema()
    {
		$settings = json_decode(parent::notes_schema(), true);
		return json_encode($settings);
	}

    public function create_form($subject_id, $aspect_type_id=null)
    {
		$output = $this->display_aspect() . '<hr />';
        $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
        $form .= \BootForm::hidden('subject_id', $subject_id);
        $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
        $form .= \BootForm::text('title', 'Title');
        $form .= \BootForm::hidden('aspect_data', 'Aspect Data');
        $form .= \BootForm::hidden('aspect_source');
        $form .= \BootForm::hidden('hidden', 'Hidden?');
        $form .= \BootForm::hidden('file_upload');
        $form .= $this->notes_fields();
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();
		$output .= $form;
        return $output;
	}

    public function edit_form()
    {
		$output = $this->display_aspect() . '<hr />';
        $form = \BootForm::horizontal(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => true]);
        $form .= \BootForm::hidden('aspect_id', $this->id);
        $form .= \BootForm::hidden('aspect_type', $this->aspect_type()->id);
        $form .= \BootForm::hidden('title', 'Title', $this->title);
        $form .= \BootForm::hidden('aspect_data', 'Aspect Data', $this->aspect_data);
        $form .= \BootForm::hidden('aspect_source', 'Source', $this->aspect_source);
        $form .= \BootForm::hidden('hidden', 'Hidden?', $this->hidden);
        $form .= \BootForm::hidden('file_upload');
        $form .= $this->notes_fields();
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();
        //return $form;
        $output = $output + $form;
        return $output;
	}

    public function display_aspect()
    {
        $output = $this->lambda_function();
		return $output;
	}

    /**
     * Override the lambda function here to implement your own logic.
     * Good basis for building new aspects based on API calls, etc.
     */
    public function lambda_function(){
		return 'lambda_function output';
	}

    public function parse()
    {
        return parent::parse();
		//if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
			// do something?
		//}
		//$this->last_parsed = Carbon::now();
		//$this->update_aspect();
    }
    
    public function pre_save(Request &$request)
    {
        return false;
    }
    public function post_save(Request &$request)
    {
        return false;
    }
    public function pre_update(Request &$request)
    {
        return false;
    }
    public function post_update(Request &$request)
    {
        return false;
    }
    public function pre_delete(Request &$request)
    {
        return false;
    }

}
