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

class RelationshipAspect extends Aspect{
    /*
		So, for relationships, this is how we're going to handle it.
		When you create a relationship from Subject A to Subject B, it will happen twice.
		First, you create the relationship on A.
		The ID of the thing that it is related to will be stored in the aspect_source field.
		The description of the relationship will be stored in the aspect_data field.
		Then, in the parse function, we'll look for a matching relationship.
		If we don't find one, we'll create one that describes the reverse relationship.
		SO:
			create the relationship. Store the ID of the target.
			upon parse, see if there is a relationship aspect type on the target that references
			  this id.
			if that doesn't exist, create it. Copy the description from the aspect_data field.
		When you display it, display the relationship description and title of the target as a link.
	*/

    function __construct()
    {
		parent::__construct();
	}

    public function notes_schema()
    {
		$settings = json_decode(parent::notes_schema(), true);
        $settings['reciprocal_aspect_id'] = '';
        return json_encode($settings);
	}

    public function create_form($subject_id, $aspect_type_id=null)
    {
		$form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => false]);
        $form .= \BootForm::hidden('subject_id', $subject_id);
        $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
        $form .= \BootForm::hidden('title', '');
        $form .= \BootForm::label('query', 'Related to: ');
        $form .= '<subject-autocomplete-field></subject-autocomplete-field>';
        $form .= \BootForm::text('aspect_data', 'As:');
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();
        return $form;
	}

    public function edit_form()
    {
		$form = \Form::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => false]);
        $form .= \Form::hidden('subject_id', $this->subjects()->first()->id);
        $form .= \Form::hidden('aspect_type', $this->aspect_type);
        $form .= '<p>';
        $form .= \BootForm::label('aspect_source', 'Related to: ');
        $form .= '<subject-autocomplete-field id="aspect_source" name="aspect_source" v-bind:initial_value="'.$this->aspect_source.'"></subject-autocomplete-field>';
        $form .= '</p>';
        $form .= '<p>';
        $form .= \Form::label('aspect_data', 'As:');
        $form .= \Form::text('aspect_data', $this->aspect_data);
        $form .= '<p>';
        $form .= \Form::hidden('hidden', '0');
        $form .= \Form::hidden('file_upload');
        $form .= $this->notes_fields();
        $form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
        $form .= \Form::close();
        return $form;
	}

    public function display_aspect()
    {
        $target = Subject::where('name', '=', $this->aspect_source)->first();
        $output = '<p>Related to <a href="/subject/'.$target->id.'">'.$this->aspect_source.'</a> as '.$this->aspect_data.'</p>';
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
        // we need to ensure that we're pointing at a subject that exists,
        // so we'll do a little validation here.
        Validator::make($request->all(), [
            'query' => 'exists:subjects,name',
        ], [
            'exists' => 'You cannot link to a subject that doesn\'t exist.',
        ])->validate();

        // We are using a non-standard form input id to hold our target subject name,
        // So we'll set it correctly here before we save.
        $this->aspect_source = $request->input('query');
    }
    public function post_save(Request &$request)
    {
        // Let's check to see if there's a reciprocal aspect.
        $settings = $this->get_aspect_notes_array();
        if (empty($settings['reciprocal_aspect_id'])){
            Log::info('Creating a reciprocal relationship aspect.');
            $target = Subject::where('name', '=', $this->aspect_source)->first();
            $this_subject = $this->subjects()->first();
            $new_aspect = AspectFactory::make_from_aspect_type($this->aspect_type);
            $new_aspect->aspect_source = $this_subject->name;
            $new_aspect->aspect_data = $this->aspect_data;
            $new_aspect->aspect_notes = ['reciprocal_aspect_id' => $this->id];
            $new_aspect->user = $this->user;
            $new_aspect->save();
            $target->aspects()->attach($new_aspect->id);
            $settings['reciprocal_aspect_id'] = $new_aspect->id;
            $this->aspect_notes = $settings;
            $this->save();
        } else {
          return;
        }
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
        // If we are deleting one end of the relationship, we also want to delete the other end.
        $settings = $this->get_aspect_notes_array();
        Aspect::where('id', $settings['reciprocal_aspect_id'])->delete();
    }

}
