<?php

namespace DoubleThreeDigital\Minify\Tests;

use DoubleThreeDigital\Minify\Minifiers\Html;

class HtmlMinifierTest extends TestCase
{
    /** @test */
    public function can_minify_html_string()
    {
        $htmlString = '<html>
            <head>
                <title>Test</title>
                <link rel="stylesheet" href="smth.css">
            </head>
            <body>
                <h1>Test</h1>
                <script src="smth.js">
                <script>
                    console.log("hiya")
                </script>
            </body>
        </html>';

        $minifier = new Html([]);
        $minify = $minifier->minify($htmlString);

        $this->assertIsString($minify);
        $this->assertStringContainsString('<html><head><title>Test</title><link rel="stylesheet" href="smth.css"></head> <body><h1>Test</h1> <script src="smth.js"', $minify);
    }
}
