#php
	/**
	 * @package <Namespac#end
	 */
	namespace <Namespac#end;

	/**
	 * This class represents represents a <ClassNam#end table withing a database or an instance of a single
	 * record in the <ClassNam#end table and provides database abstraction
	 *
	 * The ActiveRecordBase exposes 5 protected properties, do not define these properties in the sub class
	 * to have the properties auto determined
	 * 
	 * @property string $table Specifies the table name
	 * @property string $pkey Specifies the primary key (there can only be one primary key defined)
	 * @property array $fields Specifies field names mapped to field types
	 * @property array $rules Specifies field names mapped to field rules
	 * @property array $relationahips Specifies table relationships
	 *
	 * @package			<Namespac#end
	 */
	class <ClassNam#end extends \System\ActiveRecord\ActiveRecordBase
	{
		/**
		 * Specifies the table name
		 * @var string
		**/
		protected $table			= '<TableNam#end';

		/**
		 * Specifies the primary key (there can only be one primary key defined)
		 * @var string
		**/
		protected $pkey				= '<PrimaryKe#end';

		/**
		 * Specifies field names mapped to field types
		 * @var array
		**/
		protected $fields			= <Field#end;

		/**
		 * Specifies field names mapped to field rules
		 * @var array
		**/
		protected $rules			= <Rule#end;

		/**
		 * Specifies table relationships
		 * @var array
		**/
		protected $relationships	= <Relationship#end;
	}
#end