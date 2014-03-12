<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web;
	use System\Base\ModelBase;


	/**
	 * This class represents a form.
	 *
	 * The FormModelBase exposes 2 protected properties
	 * @property array $fields Contains an associative array of field names mapped to field types
	 * @property array $rules Contains an associative array of field names mapped to rules
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class FormModelBase extends ModelBase
	{
		/**
		 * Contains field mappings
		 * @var array
		 */
		static protected $field_mappings = array(
			'binary' => 'System\Web\WebControls\File',
			'blob' => 'System\Web\WebControls\TextArea',
			'boolean' => 'System\Web\WebControls\CheckBox',
			'date' => 'System\Web\WebControls\Date',
			'datetime' => 'System\Web\WebControls\DateTime',
			'email' => 'System\Web\WebControls\Email',
			'enum' => 'System\Web\WebControls\DropDownList',
			'integer' => 'System\Web\WebControls\Text',
			'numeric' => 'System\Web\WebControls\Text',
			'real' => 'System\Web\WebControls\Text',
			'ref' => 'System\Web\WebControls\DropDownList',
			'search' => 'System\Web\WebControls\Search',
			'string' => 'System\Web\WebControls\Text',
			'tel' => 'System\Web\WebControls\Tel',
			'time' => 'System\Web\WebControls\Time'
		);

		/**
		 * Contains field mappings
		 * @var array
		 */
		static protected $column_mappings = array(
			'binary' => 'System\Web\WebControls\GridViewColumn',
			'blob' => 'System\Web\WebControls\GridViewTextArea',
			'boolean' => 'System\Web\WebControls\GridViewCheckBox',
			'date' => 'System\Web\WebControls\GridViewDate',
			'datetime' => 'System\Web\WebControls\GridViewDateTime',
			'email' => 'System\Web\WebControls\GridViewEmail',
			'enum' => 'System\Web\WebControls\GridViewDropDownMenu',
			'integer' => 'System\Web\WebControls\GridViewText',
			'numeric' => 'System\Web\WebControls\GridViewText',
			'real' => 'System\Web\WebControls\GridViewText',
			'ref' => 'System\Web\WebControls\GridViewDropDownMenu',
			'search' => 'System\Web\WebControls\GridViewSearch',
			'string' => 'System\Web\WebControls\GridViewText',
			'tel' => 'System\Web\WebControls\GridViewTel',
			'time' => 'System\Web\WebControls\GridViewTime',
		);


		/**
		 * static method to return a Form object
		 *
		 * @param  string		$controlId		form id
		 * @return Form
		 */
		static public function form( $controlId )
		{
			$type = self::getClass();
			$model = new $type();
//			$legend = \substr( strrchr( self::getClass(), '\\'), 1 );

			$form = new \System\Web\WebControls\Form( $controlId );
			$form->add( new \System\Web\WebControls\Fieldset( 'fieldset' ));
//			$form->fieldset->legend = \ucwords( \System\Web\WebApplicationBase::getInstance()->translator->get( $legend, $legend ));

			// create controls
			foreach( $model->fields as $field => $type )
			{
				if(isset(self::$field_mappings[$type]))
				{
					$form->fieldset->add(new self::$field_mappings[$type]($field));
//					$form->fieldset->getControl( $field )->label = ucwords( \System\Web\WebApplicationBase::getInstance()->translator->get( $field, str_replace( '_', ' ', $field )));
				}
				else
				{
					throw new \System\Base\InvalidOperationException("No field mapping assigned to `{$type}`");
				}
			}

			// implement rules
			foreach( $model->rules as $field => $rules )
			{
				$validators = array();
				if(\is_array($rules))
				{
					foreach($rules as $rule)
					{
						$type = \strstr($rule, '(', true);
						if(!$type)
						{
							$type = $rule;
						}
						$params = \strstr($rule, '(');
						if(!$params)
						{
							$params = '()';
						}

						if(isset(self::$rule_mappings[$type]))
						{
							$validators[] = self::$rule_mappings[$type].$params;
						}
						else
						{
							throw new \System\Base\InvalidOperationException("No rule mapping assigned to `{$type}`");
						}
					}
				}
				else
				{
					$type = \strstr($rules, '(', true);
					if(!$type)
					{
						$type = $rules;
					}
					$params = \strstr($rules, '(');
					if(!$params)
					{
						$params = '()';
					}

					if(isset(self::$rule_mappings[$type]))
					{
						$validators[] = self::$rule_mappings[$type].$params;
					}
					else
					{
						throw new \System\Base\InvalidOperationException("No rule mapping assigned to `{$type}`");
					}
				}

				foreach( $validators as $validator )
				{
					if($form->fieldset->hasControl($field))
					{
						eval("\$validator = new {$validator};");
						if($validator instanceof \System\Validators\ValidatorBase)
						{
							$form->fieldset->getControl($field)->validators->add($validator);
						}
					}
				}
			}

			return $form;
		}


		/**
		 * register new field type
		 *
		 * @param  string $field field type
		 * @param  string $type path to control
		 * @return void
		 */
		final static public function registerFieldType($field, $type)
		{
			self::$field_mappings[$field] = $type;
		}


		/**
		 * register new column type
		 *
		 * @param  string $field field type
		 * @param  string $type path to control
		 * @return void
		 */
		final static public function registerColumnType($field, $type)
		{
			self::$column_mappings[$field] = $type;
		}


		/**
		 * register new rule type
		 *
		 * @param  string $rule name of rule
		 * @param  string $type path to control
		 * @return void
		 */
		final static public function registerRuleType($rule, $type)
		{
			self::$rule_mappings[$rule] = $type;
		}
	}
?>