<?php

class PPForm {

    private $form;
    private $settings;
    private $action;
    private $parsed;
    private $fields;


    public function run() {
        $this->getform();
        $this->getSettings();
        $this->getAction();
        $parser = new FormParser();
        $this->parsed = $parser->parse($form);
        $fieldFactory = new FieldFactory();
        $this->fields = $fieldFactory->generateFields($this->parsed);
        $form = new Form($this->fields);
        $form->validate();
        $renderer = new FormRenderer($form);
        $output = $renderer->render();
    }

}