<?php
/**
 * TimeIt Calendar Module
 *
 * @copyright (c) TimeIt Development Team
 * @link http://code.zikula.org/timeit
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package TimeIt
 * @subpackage Models
 */

/**
 * Join Table for the many-to-many relationship
 * categorisable entities -> category.
 */
class Categories_Models_EntityCategory extends Doctrine_Record
{
    /**
     * Setup table definition.
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->setTableName('categories_mapobj');
        
        $this->hasColumn('cmo_table as table', 'string', 60, array('primary' => true));
        $this->hasColumn('cmo_obj_id as obj_id', 'integer', 4, array('primary' => true));
        $this->hasColumn('cmo_category_id as category_id', 'integer', 4, array('primary' => true));
        $this->hasColumn('cmo_reg_id as reg_id','integer', 4, array('primary' => true));

        $this->hasColumn('cmo_reg_property as reg_property', 'string', 60);
        $this->hasColumn('cmo_modname as module', 'string', 60);
        
        $this->setSubclasses(ModUtil::getVar('Categories', 'EntityCategorySubclasses', array()));
    }

    /**
     * Setup relationships.
     *
     * @return void
     */
    public function setUp()
    {
        $this->hasOne('Categories_Models_Registry as Registry', array(
            'local' => 'reg_id',
            'foreign' => 'id'
        ));

        $this->hasOne('Categories_Models_Category as Category', array(
            'local' => 'category_id',
            'foreign' => 'id'
        ));
    }

    public function preSave(Doctrine_Event $event)
    {
        $subclasses = ModUtil::getVar('Categories', 'EntityCategorySubclasses', array());

        // get the registry object
        $registry = Doctrine::getTable('Categories_Models_Registry')->findOneByModuleAndTableAndProperty($subclasses[get_class($this)]['module'],
                                                                                                         $subclasses[get_class($this)]['table'],
                                                                                                         $this->reg_property);

        $this['Registry'] = $registry;
    }
}
