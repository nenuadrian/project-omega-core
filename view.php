<?php declare (strict_types = 1);

abstract class View
{
    public static function render(string $view, array $variables = []): void
    {
        extract($variables);
        $viewFile = SRC_DIR . "/views/$view.php";
        if ($lang = Language::lang()) {
            $langViewFile = SRC_DIR . "/views/$lang/$view.php";
            if (file_exists($langViewFile)) {
                $viewFile = $langViewFile;
            }
        }

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("View file not found: $view");
        }
    }
}
