<?php

require_once DOKU_INC.'inc/form.php';

class form_test extends UnitTestCase {

  function _testform() {
    $form = new Doku_Form('dw__testform','/test');
    $form->startFieldset('Test');
    $form->addHidden('summary', 'changes &c');
    $form->addElement(form_makeTextField('t', 'v', 'Text', 'text__id', 'block'));
    $form->addElement(form_makeCheckboxField('r', '1', 'Check', 'check__id', 'simple'));
    $form->addElement(form_makeButton('submit', 'save', 'Save', array('accesskey'=>'s')));
    $form->addElement(form_makeButton('submit', 'cancel', 'Cancel'));
    $form->endFieldset();
    return $form;
  }

  function _realoutput() {
    global $lang;
    $realoutput  = '<form action="/test" method="post" ';
    $realoutput .= 'accept-charset="'.$lang['encoding'].'" id="dw__testform">';
    $realoutput .= "\n";
    $realoutput .= '<div class="no"><input type="hidden" name="summary" value="changes &amp;c" /></div>';
    $realoutput .= "\n";
    $realoutput .= "<fieldset ><legend>Test</legend>\n";
    $realoutput .= '<label class="block" for="text__id"><span>Text</span> ';
    $realoutput .= '<input type="text" class="edit" id="text__id" name="t" value="v" /></label><br />';
    $realoutput .= "\n";
    $realoutput .= '<label class="simple" for="check__id">';
    $realoutput .= '<input type="checkbox" id="check__id" name="r" value="1" /> ';
    $realoutput .= '<span>Check</span></label>';
    $realoutput .= "\n";
    $realoutput .= '<input class="button" name="do[save]" type="submit" value="Save" accesskey="s" title="Save [ALT+S]" />';
    $realoutput .= "\n";
    $realoutput .= '<input class="button" name="do[cancel]" type="submit" value="Cancel" />';
    $realoutput .= "\n";
    $realoutput .= "</fieldset>\n</form>\n";
    return $realoutput;
  }

  function test_form_print() {
    $form = $this->_testform();
    ob_start();
    $form->printForm();
    $output = ob_get_contents();
    ob_end_clean();
    $this->assertEqual($output,$this->_realoutput());
  }

  function test_get_element_at() {
    $form = $this->_testform();
    $e1 =& $form->getElementAt(1);
    $this->assertEqual($e1, array('_elem'=>'textfield',
                                 '_text'=>'Text',
                                 '_class'=>'block',
                                 'id'=>'text__id',
                                 'name'=>'t',
                                 'value'=>'v'));
    $e2 =& $form->getElementAt(99);
    $this->assertEqual($e2, array('_elem'=>'closefieldset'));
  }

  function test_find_element_by_type() {
    $form = $this->_testform();
    $this->assertEqual($form->findElementByType('button'), 3);
    $this->assertFalse($form->findElementByType('text'));
  }

  function test_find_element_by_id() {
    $form = $this->_testform();
    $this->assertEqual($form->findElementById('check__id'), 2);
    $this->assertFalse($form->findElementById('dw__testform'));
  }

  function test_find_element_by_attribute() {
    $form = $this->_testform();
    $this->assertEqual($form->findElementByAttribute('value','Cancel'), 4);
    $this->assertFalse($form->findElementByAttribute('name','cancel'));
  }

  function test_close_fieldset() {
    $form = new Doku_Form('dw__testform','/test');
    $form->startFieldset('Test');
    $form->addHidden('summary', 'changes &c');
    $form->addElement(form_makeTextField('t', 'v', 'Text', 'text__id', 'block'));
    $form->addElement(form_makeCheckboxField('r', '1', 'Check', 'check__id', 'simple'));
    $form->addElement(form_makeButton('submit', 'save', 'Save', array('accesskey'=>'s')));
    $form->addElement(form_makeButton('submit', 'cancel', 'Cancel'));
    ob_start();
    $form->printForm();
    $output = ob_get_contents();
    ob_end_clean();
    $this->assertEqual($output,$this->_realoutput());
  }

}