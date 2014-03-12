<?php

class CompareController extends DooController {
    public function compare() {
        return $this->view()->renderLayout('main', 'compare');
    }
}
