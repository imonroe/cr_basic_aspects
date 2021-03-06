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
use League\CommonMark\CommonMarkConverter;


class MarkdownTextAspect extends UnformattedTextAspect{

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
		return parent::create_form($subject_id, $this->aspect_type);
	}

    public function edit_form()
    {
		return parent::edit_form();
	}

    public function display_aspect()
    {
        $markdown_converter = new CommonMarkConverter();
        $output = $markdown_converter->convertToHtml($this->aspect_data);
        return $output;
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
