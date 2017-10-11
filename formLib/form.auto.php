<?php

include_once "dom.utils.php";
include_once "form.classes.php";

//deleting error fields unecessary now?

class AutoForm extends Form {

	protected $name;
	protected $elements;
	protected $errorElements;
	protected $config;
	protected $DOM;

	function __construct($name, $dom, $config) {
		$this->DOM = $dom;
		$this->config = $config;
		$this->name = $name;

		$this->elements = DOMUtils::getElementsByHasAttributes(
			$this->DOM, array(
				$this->config["attributes"]["ppForm"]
			)
		);

		foreach ($this->elements as $element) {    
			$field = $this->buildFieldFromElement($element);
			$this->addField($field);
		}

		$this->findErrorElements();
	}

	//extract to field factory
	protected function buildFieldFromElement($element) {
		$classTable = $this->config['validator-types'];
		$attr_config = $this->config['attributes'];
		$attrs = DOMUtils::getAttributesAsArray($element);
		$field;

		$name = $attrs['name'];
		$required = array_key_exists($attr_config['required'] ,$attrs);
		$validation = '';

		if(!array_key_exists($attr_config['validator-type'], $attrs)) {
			//unsafe? force existence of key throw error here
			$field = new GenericField($name, $required);
		} else {
			$validation = $attrs[$attr_config['validator-type']];
			$className = $classTable[$validation];
			$field = new $className($name, $required);
		}

		$field->addMainElement($element);

		return $field;
	}

	//form render? do fields need to know about error elements?
	public function findErrorElements() {

		$errorAttr = $this->config["attributes"]["errorEle"];

		$elements = DOMUtils::getElementsByHasAttributes(
			$this->DOM, 
			array(
				$errorAttr
				)
		);
		
		foreach ($elements as $element) {
			$this->errorElements[] = $element;
			$name = $element->getAttribute($errorAttr);
			$field = $this->fields[$name];
			$field->addErrorElement($element);
		}
	}

	//form renderer
	public function handleErrors($hideEMs = true, $retainData = true) {
		if (!$this->valid) {
			$this->addErrorClass();
			if ($hideEMs) {
				$this->setErrorMessages();
			}
			if ($retainData) {
				$this->retainValues();
			}
		} 

		return $this->theErrors;
	}

	protected function addErrorClass() {
		$errorClass = $this->config['error-message']["error-class"];
		foreach ($this->errorFields as $field) {
			DOMUtils::addClass($field->mainElement, $errorClass);
		}
	}

	protected function setErrorMessages() {
		foreach ($this->fields as $field) {
			if (count($field->errorElements) > 0) {
				foreach ($field->errorElements as $errorEle) {
					if ($field->valid && isset($errorEle)) {
						DOMUtils::deleteElement($errorEle);
					} elseif (isset($errorEle)) {
						$errorType = $field->error;
						$fieldName = $field->name;
						if ($errorType == 'required') {
							$message = $fieldName . $this->config['error-message']['require-message'];
						} else {
							$message = $fieldName . $this->config['error-message']['invalid-message'];
						}
						$messageNode = new DOMText($message);
						$errorEle->appendChild($messageNode);
					}
				}
			}
		}
	}

	protected function retainValues() {
		foreach ($this->fields as $field) {
			$tag = $field->mainElement->tagName;
			$type = $field->mainElement->getAttribute("type");

			if($type == "text"){
				$val = ($field->valid) ? $field->value : $field->predata;
				$field->mainElement->setAttribute("value", $val);
				continue;
			} elseif ($tag == "textarea") {
				$text = ($field->valid) ? $field->value : $field->predata;
				$content = new DOMText($text);
				$field->mainElement->appendChild($content);
				continue;
			} elseif ($type == "radio") {
				$val = ($field->valid) ? $field->value : $field->predata;
				$r_elements = DOMUtils::filterByAttributeValues($this->elements, array("value" => $val));
				$r_elements[0]->setAttribute("checked", "checked");
				continue;
			} elseif ($type = "checkbox" && $tag != "select") {
				$ch_elements = DOMUtils::filterByAttributeValues($this->elements, array("name" => $field->name."[]"));
				$values = ($field->valid) ? $field->value : $field->predata;
				$values = explode(" ", $values);
  
				foreach ($ch_elements as $ch_element) {
					$ch_element->removeAttribute("checked");
					foreach($values as $value) {
						if ($ch_element->getAttribute("value") == $value) {
							$ch_element->setAttribute("checked", "checked");
						}
					}             
				}
				continue;
			} elseif ($tag == "select") {
				$value = ($field->valid) ? $field->value : $field->predata;
				$children = $field->mainElement->childNodes;
				foreach($children as $child) {
					foreach($child->childNodes as $gc) {
						if (is_a($gc, 'DOMElement')) {
							if ($gc->getAttribute("value") == $value) {
								$gc->setAttribute("selected", "selected");
							}
						}
					}
				}
				continue;
			}
		}
	}
	//form renderer

	public function checkValid() {
		$this->process();
		if (!$this->valid) {
			return false;
		} else {     
			return true;
		}
	}

	public function renderDefaultConfirmation() {
		$this->retainValues();

		foreach ($this->elements as $element) {
			$element->setAttribute('readonly', 'readonly');
		}
		return $this->DOM->saveHTML();
	}

	public function renderErrorForm() {
		$this->handleErrors();
		return $this->DOM->saveHTML();
	}

	public function renderBaseForm($usejs) {
		if ( $useJS ){
			if (count($this->errorElements) > 0) {
				foreach ($this->errorElements as $element) {
					DOMUtils::deleteElement($element);
				}
			}
		}
		return $this->DOM->saveHTML();
	}
}