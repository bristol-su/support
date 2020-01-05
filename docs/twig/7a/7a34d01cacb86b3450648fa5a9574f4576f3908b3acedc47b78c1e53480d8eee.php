<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* /interface.html.twig */
class __TwigTemplate_18dc10507444c3f264206fb5bb83b0c96fd584ef402d31cf38af54eed3dce150 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'javascripts' => [$this, 'block_javascripts'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html.twig", "/interface.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_javascripts($context, array $blocks = [])
    {
        // line 4
        echo "    <script type=\"text/javascript\">
        function loadExternalCodeSnippets() {
            Array.prototype.slice.call(document.querySelectorAll('pre[data-src]')).forEach(function (pre) {
                var src = pre.getAttribute('data-src').replace( /\\\\/g, '/');
                var extension = (src.match(/\\.(\\w+)\$/) || [, ''])[1];
                var language = 'php';

                var code = document.createElement('code');
                code.className = 'language-' + language;

                pre.textContent = '';

                code.textContent = 'Loading…';

                pre.appendChild(code);

                var xhr = new XMLHttpRequest();

                xhr.open('GET', src, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {

                        if (xhr.status < 400 && xhr.responseText) {
                            code.textContent = xhr.responseText;

                            Prism.highlightElement(code);
                        }
                        else if (xhr.status >= 400) {
                            code.textContent = '✖ Error ' + xhr.status + ' while fetching file: ' + xhr.statusText;
                        }
                        else {
                            code.textContent = '✖ Error: File does not exist, is empty or trying to view from localhost';
                        }
                    }
                };

                xhr.send(null);
            });
        }

        \$(document).ready(function(){
            loadExternalCodeSnippets();
        });
        \$('#source-view').on('shown', function () {
            loadExternalCodeSnippets();
        })
    </script>
";
    }

    // line 54
    public function block_content($context, array $blocks = [])
    {
        // line 55
        echo "    <section class=\"row-fluid\">
        <div class=\"span2 sidebar\">
            ";
        // line 57
        $context["namespace"] = $this->getAttribute(($context["project"] ?? null), "namespace", []);
        // line 58
        echo "            ";
        $this->displayBlock("sidebarNamespaces", $context, $blocks);
        echo "
        </div>
    </section>
    <section class=\"row-fluid\">
        <div class=\"span10 offset2\">
            <div class=\"row-fluid\">
                <div class=\"span8 content class\">
                    <nav>
                        ";
        // line 67
        echo "                        ";
        echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "namespace", [])]);
        echo "
                        ";
        // line 69
        echo "                    </nav>
                    ";
        // line 70
        if ($this->getAttribute($this->getAttribute(($context["project"] ?? null), "settings", []), "shouldIncludeSource", [])) {
            // line 71
            echo "                        <a href=\"#source-view\" role=\"button\" class=\"pull-right btn\" data-toggle=\"modal\"><i class=\"icon-code\"></i></a>
                    ";
        }
        // line 73
        echo "
                    <h1><small>";
        // line 74
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "namespace", []), "html", null, true);
        echo "</small>";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "</h1>
                    <p><em>";
        // line 75
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "summary", []), "html", null, true);
        echo "</em></p>
                    ";
        // line 76
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "description", [])]);
        echo "

                    <section id=\"summary\">
                        <h2>Summary</h2>
                        <section class=\"row-fluid heading\">
                            <section class=\"span6\">
                                <a href=\"#methods\">Methods</a>
                            </section>
                            <section class=\"span6\">
                                <a href=\"#constants\">Constants</a>
                            </section>
                        </section>
                        <section class=\"row-fluid public\">
                            <section class=\"span6\">
                                ";
        // line 90
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "public")) {
                // line 91
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 93
            echo "                                    <em>No public methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 95
        echo "                            </section>
                            <section class=\"span6\">
                                ";
        // line 97
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedConstants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "constants", [])], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 98
            echo "                                    <a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["constant"], "url"]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo "</a><br />
                                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 100
            echo "                                    <em>No constants found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 102
        echo "                            </section>
                        </section>
                        <section class=\"row-fluid protected\">
                            <section class=\"span6\">
                                ";
        // line 106
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "protected")) {
                // line 107
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 109
            echo "                                    <em>No protected methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 111
        echo "                            </section>
                            <section class=\"span6\">
                                <em>N/A</em>
                            </section>
                        </section>
                        <section class=\"row-fluid private\">
                            <section class=\"span6\">
                                ";
        // line 118
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "private")) {
                // line 119
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 121
            echo "                                    <em>No private methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 123
        echo "                            </section>
                            <section class=\"span6\">
                                <em>N/A</em>
                            </section>
                        </section>
                    </section>
                </div>
                <aside class=\"span4 detailsbar\">
                    ";
        // line 131
        if ($this->getAttribute(($context["method"] ?? null), "deprecated", [])) {
            // line 132
            echo "                        <aside class=\"alert alert-block alert-error\">
                            <h4>Deprecated</h4>
                            ";
            // line 134
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "description", []), "html", null, true);
            echo "
                        </aside>
                    ";
        }
        // line 137
        echo "                    <dl>
                        <dt>File</dt>
                            <dd>
                                <a href=\"";
        // line 140
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "file", []), "url"]), "html", null, true);
        echo "\"><div class=\"path-wrapper\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "path", []), "html", null, true);
        echo "</div></a>
                            </dd>
                        ";
        // line 142
        if (( !twig_test_empty($this->getAttribute(($context["node"] ?? null), "package", [])) && ($this->getAttribute(($context["node"] ?? null), "package", []) != "\\"))) {
            // line 143
            echo "                        <dt>Package</dt>
                            <dd><div class=\"namespace-wrapper\">";
            // line 144
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["node"] ?? null), "subpackage", [])) ? ((($this->getAttribute(($context["node"] ?? null), "package", []) . "\\") . $this->getAttribute(($context["node"] ?? null), "subpackage", []))) : ($this->getAttribute(($context["node"] ?? null), "package", []))), "html", null, true);
            echo "</div></dd>
                        ";
        }
        // line 146
        echo "                        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "parent", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["parent"]) {
            // line 147
            echo "                            ";
            if ($this->getAttribute($context["loop"], "first", [])) {
                // line 148
                echo "                        <dt>Parents</dt>
                            ";
            }
            // line 150
            echo "                            <dd><a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["parent"], "url"]), "html", null, true);
            echo "\"><div class=\"namespace-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["parent"], "fullyQualifiedStructuralElementName", []), "html", null, true);
            echo "</div></a></dd>
                        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parent'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 152
        echo "
                        ";
        // line 153
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "link", 1 => "see"])) {
                // line 154
                echo "                            ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 155
                    echo "                        <dt>See also</dt>
                            ";
                }
                // line 157
                echo "                            ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 158
                    echo "                            <dd><a href=\"";
                    echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) ? (call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) : ($this->getAttribute($context["tag"], "link", []))), "html", null, true);
                    echo "\"><div class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</div></a></dd>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 160
                echo "                        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 161
        echo "                    </dl>
                    <h2>Tags</h2>
                    <table class=\"table table-condensed\">
                    ";
        // line 164
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "abstract", 3 => "method", 4 => "package", 5 => "subpackage"])) {
                // line 165
                echo "                        <tr>
                            <th>";
                // line 166
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "</th>
                            <td>
                                ";
                // line 168
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 169
                    echo "                                    ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 170
                    echo "                                    ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 172
                echo "                            </td>
                        </tr>
                    ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 175
            echo "                        <tr><td colspan=\"2\"><em>None found</em></td></tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 177
        echo "                    </table>
                </aside>
            </div>

            ";
        // line 181
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0)) {
            // line 182
            echo "                <a id=\"constants\" name=\"constants\"></a>
                <div class=\"row-fluid\">
                <div class=\"span8 content class\">
                    <h2>Constants</h2>
                </div>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

            ";
            // line 190
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedConstants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "constants", [])], "method"));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                // line 191
                echo "                ";
                $this->displayBlock("constant", $context, $blocks);
                echo "
                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 193
            echo "            ";
        }
        // line 194
        echo "
            ";
        // line 195
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method")) > 0)) {
            // line 196
            echo "            <div class=\"row-fluid\">
                <div class=\"span8 content class\">
                    <h2>Methods</h2>
                </div>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 203
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "public")) {
                    // line 204
                    echo "                ";
                    $this->displayBlock("method", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 206
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "protected")) {
                    // line 207
                    echo "                ";
                    $this->displayBlock("method", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 209
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "methods", [])], "method"));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "private")) {
                    // line 210
                    echo "                ";
                    $this->displayBlock("method", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 212
            echo "            ";
        }
        // line 213
        echo "        </div>
    </section>

    <div id=\"source-view\" class=\"modal hide fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"source-view-label\" aria-hidden=\"true\">
        <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
            <h3 id=\"source-view-label\">";
        // line 219
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "file", []), "name", []), "html", null, true);
        echo "</h3>
        </div>
        <div class=\"modal-body\">
            <pre data-src=\"";
        // line 222
        echo call_user_func_array($this->env->getFunction('path')->getCallable(), [(("files/" . $this->getAttribute(($context["node"] ?? null), "path", [])) . ".txt")]);
        echo "\" class=\"language-php\"></pre>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "/interface.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  620 => 222,  614 => 219,  606 => 213,  603 => 212,  590 => 210,  578 => 209,  565 => 207,  553 => 206,  540 => 204,  529 => 203,  520 => 196,  518 => 195,  515 => 194,  512 => 193,  495 => 191,  478 => 190,  468 => 182,  466 => 181,  460 => 177,  453 => 175,  445 => 172,  436 => 170,  431 => 169,  427 => 168,  422 => 166,  419 => 165,  413 => 164,  408 => 161,  398 => 160,  387 => 158,  382 => 157,  378 => 155,  375 => 154,  364 => 153,  361 => 152,  342 => 150,  338 => 148,  335 => 147,  317 => 146,  312 => 144,  309 => 143,  307 => 142,  300 => 140,  295 => 137,  289 => 134,  285 => 132,  283 => 131,  273 => 123,  266 => 121,  255 => 119,  249 => 118,  240 => 111,  233 => 109,  222 => 107,  216 => 106,  210 => 102,  203 => 100,  193 => 98,  188 => 97,  184 => 95,  177 => 93,  166 => 91,  160 => 90,  143 => 76,  139 => 75,  133 => 74,  130 => 73,  126 => 71,  124 => 70,  121 => 69,  116 => 67,  104 => 58,  102 => 57,  98 => 55,  95 => 54,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/interface.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//interface.html.twig");
    }
}
