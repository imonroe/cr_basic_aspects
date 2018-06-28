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

class FileUploadAspect extends Aspect{

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
		$form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
        $form .= \BootForm::hidden('subject_id', $subject_id);
        $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
        $form .= \BootForm::hidden('media_collection', 'uploads');
        $form .= \BootForm::hidden('mime_type', 'all');
        $form .= \BootForm::text('title', 'Title');
        $form .= \BootForm::textarea('aspect_data', 'Description');
        //$form .= \BootForm::text('aspect_source', 'File');
        $form .= \BootForm::file('file_upload');
        $form .= $this->notes_fields();
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();
        return $form;
	}

    public function edit_form()
    {
		$media_items = $this->media;
        $output = '<h3>Currently attached files:</h3>';
        $output .= '<ul>'.PHP_EOL;
        foreach ($media_items as $file){
        $output .= '<li><a href="'.$file->getUrl().'">'.$file->name.' </a> ('.$file->mime_type.', '.$file->human_readable_size.')</li>';
        }
        $output .= '</ul>'.PHP_EOL;

        $form = \BootForm::horizontal(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => true]);
        $form .= \BootForm::hidden('aspect_id', $this->id);
        $form .= \BootForm::hidden('aspect_type', $this->aspect_type()->id);
        $form .= \BootForm::hidden('media_collection', 'uploads');
        $form .= \BootForm::hidden('mime_type', 'all');
        $form .= \BootForm::text('title', 'Title', $this->title);
        $form .= \BootForm::textarea('aspect_data', 'Description', $this->aspect_data);
        //$form .= \BootForm::text('aspect_source', 'Source', $this->aspect_source);
        //$form .= \BootForm::checkbox('hidden', 'Hidden?', $aspect->hidden);
        //$form .= \BootForm::file('file_upload', 'Add Another File');
        $form .= $this->notes_fields();
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();

        $out = $output . $form;
        return $out;
	}

    public function display_aspect()
    {
        // There will be uploaded file(s) associated with this aspect, so let's grab them.
        $media_items = $this->media;
        $output = '<div class="aspect_type-'.$this->aspect_type()->id.'">';
        $output .= '<p>'.$this->aspect_data.'</p>'.PHP_EOL;
        $output .= '<ul>'.PHP_EOL;
        foreach ($media_items as $file){
            $output .= '<li><a href="'.$file->getUrl().'">'.$file->name.' </a> ('.$file->mime_type.', '.$file->human_readable_size.')</li>';
        }
        $output .= '</ul>'.PHP_EOL;
		$output .= '</div>';
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
