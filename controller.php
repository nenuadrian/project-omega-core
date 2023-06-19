<?php declare (strict_types = 1);

abstract class Controller
{
    protected $tVars = [];

    protected function guard(): bool
    {return true;}

    public function init(): void
    {}

    public function routes( ? string $page,  ? array $params = []) : void
    {
        $page = $page ? $page : 'index';
        $page = method_exists($this, $page) ? $page : 'index';
        if (method_exists($this, $page)) {
            try {
                if ($this->guard()) {
                    call_user_func_array([$this, $page], $params);
                } else {
                    $this->guardFail();
                }
            } catch (Exception $error) {
                $message = $error->getMessage();
                $this->json(500, [], $message);
            }
        } else {
            die('404 - action not found');
        }

    }

    protected function redirect(string $url) : void
    {
        if (strpos($url, 'http') === false) {
            $url = BASE_URL . '/' . $url;
        }

        if (!headers_sent()) {
            header('Location: ' . $url);
        }
    }

    protected function guardFail()
    {
        $this->json(403);
    }

    protected function json(int $status,  ? array $data = null, string $error = null)
    {
        header("Access-Control-Allow-Origin: *");

        echo json_encode([
            "status" => $status,
            "data" => $data,
            "error" => $error,
        ]);
    }
}
