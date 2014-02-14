<?php
class ContentType extends SnapModel
{
	public $name;
	public $id;
	public $content_id;
	public $Content;
	public $fileFields = array();
	protected $fields = array();
	protected $rules = array();
	protected $inputTypes = array();
	protected $_newRecord = true;
	private $_schemaErrors = array();
	private $_attributes = array();
	private $_tableSchema = null;
	private $_alias = null;
	private $_rules = array(
		array('name, id', 'required'),
		array('content_id', 'numerical'),
	);
	
	public function __construct($config = array()) 
	{
		if(empty($config)) return;
		
		foreach($config['fields'] as $name => $value) {
			$this->_attributes[$name] = '';
		}
		
		$this->_rules = array_merge($this->_rules, $config['rules']);
		
		$this->name = $config['name'];
		$this->id = $config['id'];
		
		$this->inputTypes = $config['inputTypes'];
		$this->fileFields = array_keys(array_filter($this->inputTypes, array($this,'_filterFileFields')));
		
		//parent::__construct($config);
	}
	
	public function _filterFileFields($var)
	{
		return $var=='fileField' || $var=='imageField';
	}
	
	public function rules()
	{
		return $this->_rules;
	}
	
	/**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Machine name',
            'name' => 'Name',
			'className' => 'Class name'
        );
    }
	
	public function getTableName()
	{
		return Content::TYPE_MACHINE_NAME . '_' . $this->id;
	}
	
	public static function findAll()
	{
		$cnfCTs = self::getConfigArray();
		$contentTypes = array();
		foreach($cnfCTs as $ct) {
			$contentTypes[] = new self($ct);
		}
		return $contentTypes;
	}
	
	public static function find($type)
	{		
		$cnfCTs = self::getConfigArray();
		if(isset($cnfCTs[$type])) {
			return new self($cnfCTs[$type]);
		} else {
			return false;
		}
	}
	
	public function loadData()
	{
		$cmd = Yii::app()->db->createCommand();
		$cmd
			->select('*')
			->from('{{'.$this->tableName.'}}')
			->where('content_id='.$this->content_id);
		
		$res = $cmd->queryRow();
		foreach($this->_attributes as $key=>$attr) {
			$this->_attributes[$key] = $res[$key];
		}
		 
		if($res) {
			$this->_newRecord = false;
		}
	}
	
	public static function updateSchema($deleteColumns = false)
	{
		$contentTypes = self::findAll();
	
		foreach($contentTypes as $ContentType)
		{	
			if(!$ContentType->tableExists()) {
				$ContentType->createTable();
			}
			if($ContentType->fieldsExist() !== false && $ContentType->fieldsExist() > 0) {
				$ContentType->createFields();
			}
			//Don't drop columns unless explicitly requested
			if(!$deleteColumns) {
				continue;
			}
			
			//Drop columns no longer being used
			foreach($TableSchema->columns as $column)
			{
				//We don't want to test for the foreign key
				//as it is hidden from the config
				if($column->name === 'content_id') {
					continue;
				}
				if(!isset($cnfCTs[$type]['fields'][$column->name])) {
					if(Yii::app()->db->createCommand()->dropColumn($tableName, $column->name)) {
						Yii::app()->user->setFlash('success', 
							"Dropped column <strong>$columnName</strong> in table <strong>$tableName</strong>");
					} else {
						Yii::app()->user->setFlash('error', 
							"Error dropping column <strong>$columnName</strong> in table <strong>$tableName</strong>");
					}
				}
			}
		}
	}
	
	public function fieldsExist()
	{
		$TableSchema = Yii::app()->db->schema->getTable('{{'.$this->tableName.'}}');
		if($TableSchema === null) {
			return false;
		}
		
		$fieldsMissing = 0;
		
		$ct = $this->getConfiguration();
		foreach($ct['fields'] as $columnName => $fieldDataType)
		{
			if($TableSchema->getColumn($columnName) === null) {
				$fieldsMissing++;
				$this->addSchemaError("Column <strong>$columnName</strong> does not exist in table <strong>$this->tableName</strong>");
			}
		}
		
		return $fieldsMissing == 0 ? true : $fieldsMissing;
	}
	
	public function createFields()
	{
		$TableSchema = Yii::app()->db->schema->getTable('{{'.$this->tableName.'}}');
		if($TableSchema === null) {
			return false;
		}
		
		$fieldsCreated = 0;
		
		$ct = $this->getConfiguration();
		foreach($ct['fields'] as $columnName => $fieldDataType)
		{
			if($TableSchema->getColumn($columnName) === null) {
				if(Yii::app()->db->createCommand()->addColumn('{{'.$this->tableName.'}}', $columnName, $fieldDataType)) {
					$fieldsCreated++;
				} else {
					Yii::app()->session->setFlash('error', 
						"Error creating column <strong>$columnName</strong> in table <strong>$this->tableName</strong>");
				}
			}
		}
		
		return $fieldsCreated;
	}
	
	public function tableExists()
	{
		$TableSchema = Yii::app()->db->schema->getTable('{{'.$this->tableName.'}}');
		if($TableSchema === null) {
			$this->addSchemaError("Table <strong>$this->tableName</strong> does not exist");
			return false;
		}
		return true;
	}
	
	public function createTable()
	{
		$ct = $this->getConfiguration();
		$fields = array('content_id' => 'pk') + $ct['fields'];
		return Yii::app()->db->createCommand()->createTable('{{'.$this->tableName.'}}', $fields);
	}
	
	public function getConfiguration()
	{
		$cnfCTs = self::getConfigArray();
		return $cnfCTs[$this->id];
	}
	
	public static function getConfigArray()
	{
		return SnapUtil::getConfig('content.content_types');
	}
	
	public function addSchemaError($error)
	{
		$this->_schemaErrors[] = $error;
	}
	
	public function checkForErrors()
	{
		$this->tableExists();
		$this->fieldsExist();
	}
	
	public function hasSchemaErrors()
	{
		return !empty($this->_schemaErrors);
	}
	
	public function getSchemaErrors()
	{
		return $this->_schemaErrors;
	}

	public function getAttributes()
	{
		return $this->_attributes;
	}
	
	public function __set($name, $value)
	{

		if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
			$this->_attributes[$name] = $value;
		} else {
			parent::__set($name, $value);
		}
	}
	
	public function getTableSchema() 
	{
		if($this->_tableSchema !== null) {
			return $this->_tableSchema;
		}
		$this->_tableSchema = Yii::app()->db->schema->getTable('{{'.$this->tableName.'}}');
		return $this->_tableSchema;
	}
	
	public function save()
	{
		$dataDir = Yii::getPathOfAlias('application.data');
		foreach($this->fileFields as $field) 
		{	
			$uploadFile=CUploadedFile::getInstance($this,$field);
			if(!$uploadFile) 
				continue;
			
			$this->$field=$uploadFile;
			$dirPath=$dataDir.'/content_type_files/'.$this->id;
			if (!file_exists($dirPath)) {
				mkdir($dirPath, 0777, true);
			}
			if($this->$field)
				$this->$field->saveAs($dirPath.'/'.$field);
			else
				Yii::app()->user->setFlash('danger','problem saving image for field: '.$field);
		}
		
		if($this->_newRecord) {
			$attribs = $this->_attributes;
			$attribs['content_id'] = $this->content_id;
			$saved = Yii::app()->db->createCommand()->insert('{{'.$this->tableName.'}}',$attribs);
		} else {
			$saved = Yii::app()->db->createCommand()->update('{{'.$this->tableName.'}}',$this->_attributes,'content_id='.$this->content_id);
		}
	}
		
	public function __get($name)
	{
		$getter = 'get' . $name;
		if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
			return $this->_attributes[$name];
		} elseif (method_exists($this, $getter)) {
			// read property, e.g. getName()
			return $this->$getter();
		} else {
			parent::__get($name);
		}
	}
	
	public function __isset($name) 
	{
		return isset($this->_attributes[$name]);
	}
		
	public static function getList()
	{
		return CHtml::listData(self::findAll(),'id','name');
	}
	
}
