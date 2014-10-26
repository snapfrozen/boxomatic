<?php
/**
 * This is the base model / active record class 
 * all model classes MUST extend this class.
 * 
 * @author Francis Beresford
 */

class BoxomaticActiveRecord extends SnapActiveRecord 
{
	public $tablePrefix = 'boxo_';
}