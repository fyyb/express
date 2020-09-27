<?php

class RouterTest
{
    private $file;
    public function __construct(String $file)
    {
        if (file_exists($file)) {
            $this->file = json_decode(file_get_contents($file), true);
        }
    }

    private function request($uri, $method = 'GET')
    {
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        return $res;
    }

    private function renderResult($total, $success, $method = '', $route = [], $res = [])
    {
        if (count($route) > 0) {
            $expected = json_encode(array_reverse($route['expected']), JSON_PRETTY_PRINT, 4);
            $obtained = json_encode($res, JSON_PRETTY_PRINT, 4);

            $expected = preg_replace('/\\\/', '', $expected);
            $obtained = preg_replace('/\\\/', '', $obtained);
        };

        $html = '<!DOCTYPE html>' .
        '<html lang="en">' .
        '<head>' .
        '    <meta charset="UTF-8">' .
        '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' .
        '    <title>Test Routes</title>' .
        '    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/styles/dracula.min.css">' .
        '    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>' .
        '    <style>' .
        '       *{padding:0;margin:0;box-sizing:border-box}' .
        '       html, body{min-height: 100%;height: 100%}' .
        '       body{margin:0;padding:30px;font:14px/1.5 Helvetica,Arial,Verdana,sans-serif}' .
        '       h1{margin:0;font-size:48px;font-weight:normal;line-height:48px}' .
        '       h2{margin-bottom:0; display:inline}' .
        '       h3{margin-bottom:0; display:inline}' .
        '       strong{display:inline-block}' .
        '       ul{list-style:none;padding:0;margin:5px 0px 10px 15px;}' .
        '       pre{display:flex;flex-direction: column;justify-content: center; margin-bottom:20px}' .
        '       .block-code{padding: 10px !important; width:50%; min-width: 300px}' .
        '       hr{margin-top:10px;margin-bottom:10px}' .
        '    </style>' .
        '</head>' .
        '<body>' .
        '    <h1> Router Test</h1>' .
        '    <hr/>' .
        '    <h3>Total: ' . $total . '</h3>' .
        '    <br/>' .
        '    <h3>Success: ' . $success . '  (' . round(($success / $total * 100), 2) . '%)' . '</h3>' .
            '    <hr/>';

        if (count($route) > 0) {
            $html .= '<section id="error">' .
                '       <h2>Router: ' . $route['uri'] . ' [' . $method . ']</h2>' .
                '       <br/>' .
                '       <h3>(' . $route['label'] . ')</h3>' .
                '       <br/><br/>' .
                '       <strong>Expected:</strong><br/>' .
                '       <pre><code class="json block-code">' . $expected . '</code></pre>' .
                '       <strong>Obtained:</strong><br/>' .
                '       <pre><code class="json block-code">' . $obtained . '</code></pre>' .
                '    </section>';
        };

        $html .= '    <script>hljs.initHighlightingOnLoad();</script>' .
            '</body>' .
            '</html>';
        echo $html;
        exit;
    }

    public function test()
    {
        $baseURL = $this->file['baseURL'];
        $total = 0;
        $success = 0;
        $hasError = false;
        $methodError = '';

        foreach ($this->file['groups'] as $group) {
            foreach ($group['routes'] as $route) {
                $total += count($route['methods']);
            };
        };

        foreach ($this->file['groups'] as $group) {
            if ($hasError) {
                break;
            }

            foreach ($group['routes'] as $route) {
                $uri = $baseURL . $route['uri'];
                foreach ($route['methods'] as $method) {
                    $res = $this->request($uri, $method);
                    if (array_reverse($route['expected']) === $res) {
                        $success++;
                    } else {
                        $methodError = $method;
                        $hasError = true;
                        break;
                    };
                }
            };
        };

        if ($hasError) {
            $this->renderResult($total, $success, $method, $route, $res);
        } else {
            $this->renderResult($total, $success);
        };

        exit;
    }
}

$test = new RouterTest('./routes.json');
// $test = new RouterTest('./where.json');
$test->test();
