<?php
class HomeController extends Controller {
    public function index() {
        $data = ['title' => 'Trang Chủ', 'message' => 'Chào mừng bạn đến với MVC PHP thuần!'];
        $this->view('home', $data);
    }
}