<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        // Support both old format (world) and new format (worlds/list)
        if (strpos($view, '/') !== false) {
            // New format: worlds/list -> views/worlds/list.php
            require_once '../app/views/' . $view . '.php';
        } else {
            // Old format: world -> views/world/world.php (backward compatibility)
            require_once '../app/views/' . $view . '/' . $view . '.php';
        }
    }
}
