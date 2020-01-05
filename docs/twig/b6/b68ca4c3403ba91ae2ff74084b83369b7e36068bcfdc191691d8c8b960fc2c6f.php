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

/* /class.html.twig */
class __TwigTemplate_cdbae92df3722be6104734fe7f2669a1ddfd6e3e60e39b12cddd2f7fbc800583 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'javascripts' => [$this, 'block_javascripts'],
            'content' => [$this, 'block_content'],
            'hierarchy_element' => [$this, 'block_hierarchy_element'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html.twig", "/class.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_javascripts($context, array $blocks = [])
    {
        // line 4
        echo "<script type=\"text/javascript\">
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
        echo " <i class=\"icon-level-up\"></i>
                        ";
        // line 69
        echo "                    </nav>
                    ";
        // line 70
        if ($this->getAttribute($this->getAttribute(($context["project"] ?? null), "settings", []), "shouldIncludeSource", [])) {
            // line 71
            echo "                    <a href=\"#source-view\" role=\"button\" class=\"pull-right btn\" data-toggle=\"modal\"><i class=\"icon-code\"></i></a>
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
                    
                    ";
        // line 78
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "example"])) {
                // line 79
                echo "                        ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 80
                    echo "                            <h3>Examples</h3>
                        ";
                }
                // line 82
                echo "                        ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 83
                    echo "                            <h4>";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "description", []));
                    echo "</h4>
                            <pre class=\"pre-scrollable\">";
                    // line 84
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "example", []));
                    echo "</pre>
                        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 86
                echo "                    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 87
        echo "                    
                    <section id=\"summary\">
                        <h2>Summary</h2>
                        <section class=\"row-fluid heading\">
                            <section class=\"span4\">
                                <a href=\"#methods\">Methods</a>
                            </section>
                            <section class=\"span4\">
                                <a href=\"#properties\">Properties</a>
                            </section>
                            <section class=\"span4\">
                                <a href=\"#constants\">Constants</a>
                            </section>
                        </section>
                        <section class=\"row-fluid public\">
                            <section class=\"span4\">
                                ";
        // line 103
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "public")) {
                // line 104
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["method"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 106
            echo "                                    <em>No public methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 108
        echo "                            </section>
                            <section class=\"span4\">
                                ";
        // line 110
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedProperties", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicProperties", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            if (($this->getAttribute($context["property"], "visibility", []) == "public")) {
                // line 111
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["property"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["property"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">\$";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo "</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 113
            echo "                                    <em>No public properties found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 115
        echo "                            </section>
                            <section class=\"span4\">
                                ";
        // line 117
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedConstants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "constants", [])], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 118
            echo "                                    <a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["constant"], "url"]), "html", null, true);
            echo "\" class=\"";
            echo (($this->getAttribute($context["constant"], "deprecated", [])) ? ("deprecated") : (""));
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo "</a><br />
                                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 120
            echo "                                    <em>No constants found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 122
        echo "                            </section>
                        </section>
                        <section class=\"row-fluid protected\">
                            <section class=\"span4\">
                                ";
        // line 126
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "protected")) {
                // line 127
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["method"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 129
            echo "                                    <em>No protected methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 131
        echo "                            </section>
                            <section class=\"span4\">
                                ";
        // line 133
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedProperties", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicProperties", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            if (($this->getAttribute($context["property"], "visibility", []) == "protected")) {
                // line 134
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["property"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["property"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">\$";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo "</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 136
            echo "                                    <em>No protected properties found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 138
        echo "                            </section>
                            <section class=\"span4\">
                                <em>N/A</em>
                            </section>
                        </section>
                        <section class=\"row-fluid private\">
                            <section class=\"span4\">
                                ";
        // line 145
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            if (($this->getAttribute($context["method"], "visibility", []) == "private")) {
                // line 146
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["method"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["method"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
                echo "()</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 148
            echo "                                    <em>No private methods found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 150
        echo "                            </section>
                            <section class=\"span4\">
                                ";
        // line 152
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedProperties", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicProperties", [])], "method")], "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            if (($this->getAttribute($context["property"], "visibility", []) == "private")) {
                // line 153
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["property"], "url"]), "html", null, true);
                echo "\" class=\"";
                echo (($this->getAttribute($context["property"], "deprecated", [])) ? ("deprecated") : (""));
                echo "\">\$";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo "</a><br />
                                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 155
            echo "                                    <em>No private properties found</em>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 157
        echo "                            </section>
                            <section class=\"span4\">
                                <em>N/A</em>
                            </section>
                        </section>
                    </section>
                </div>
                <aside class=\"span4 detailsbar\">
                    ";
        // line 165
        if ($this->getAttribute(($context["node"] ?? null), "abstract", [])) {
            // line 166
            echo "                        <span class=\"label label-info\">abstract</span>
                    ";
        }
        // line 168
        echo "                    ";
        if ($this->getAttribute(($context["node"] ?? null), "final", [])) {
            // line 169
            echo "                        <span class=\"label label-info\">final</span>
                    ";
        }
        // line 171
        echo "
                    ";
        // line 172
        if ($this->getAttribute(($context["method"] ?? null), "deprecated", [])) {
            // line 173
            echo "                        <aside class=\"alert alert-block alert-error\">
                            <h4>Deprecated</h4>
                            ";
            // line 175
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "description", []), "html", null, true);
            echo "
                        </aside>
                    ";
        }
        // line 178
        echo "
                    <dl>
                        <dt>File</dt>
                            <dd><a href=\"";
        // line 181
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "file", []), "url"]), "html", null, true);
        echo "\"><div class=\"path-wrapper\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "path", []), "html", null, true);
        echo "</div></a></dd>
                        ";
        // line 182
        if (( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "name", [])) && ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "name", []) != "\\"))) {
            // line 183
            echo "                        <dt>Package</dt>
                            <dd><div class=\"namespace-wrapper\">";
            // line 184
            echo twig_escape_filter($this->env, ((($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "parent", []), "name", []) != "\\")) ? ((($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "parent", []), "name", []) . "\\") . $this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "name", []))) : ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "package", []), "name", []))), "html", null, true);
            echo "</div></dd>
                        ";
        }
        // line 186
        echo "                        <dt>Class hierarchy</dt>
                            <dd class=\"hierarchy\">
                                ";
        // line 188
        $context["class"] = $this->getAttribute(($context["node"] ?? null), "parent", []);
        // line 189
        echo "                                ";
        $this->displayBlock('hierarchy_element', $context, $blocks);
        // line 197
        echo "                                <div class=\"namespace-wrapper\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "fullyQualifiedStructuralElementName", []), "html", null, true);
        echo "</div>
                            </dd>

                        ";
        // line 200
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "interfaces", []));
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
        foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
            // line 201
            echo "                            ";
            if ($this->getAttribute($context["loop"], "first", [])) {
                // line 202
                echo "                        <dt>Implements</dt>
                            ";
            }
            // line 204
            echo "                            <dd><a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["interface"], "url"]), "html", null, true);
            echo "\"><div class=\"namespace-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["interface"], "fullyQualifiedStructuralElementName", []), "html", null, true);
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 206
        echo "
                        ";
        // line 207
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "usedTraits", []));
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
        foreach ($context['_seq'] as $context["_key"] => $context["trait"]) {
            // line 208
            echo "                            ";
            if ($this->getAttribute($context["loop"], "first", [])) {
                // line 209
                echo "                                <dt>Uses traits</dt>
                            ";
            }
            // line 211
            echo "                            <dd>
                                ";
            // line 212
            if ($this->getAttribute($context["trait"], "fullyQualifiedStructuralElementName", [])) {
                echo "<a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["trait"], "url"]), "html", null, true);
                echo "\">";
            }
            // line 213
            echo "                                    <div class=\"namespace-wrapper\">";
            echo twig_escape_filter($this->env, (($this->getAttribute($context["trait"], "fullyQualifiedStructuralElementName", [])) ? ($this->getAttribute($context["trait"], "fullyQualifiedStructuralElementName", [])) : ($context["trait"])), "html", null, true);
            echo "</div>
                                ";
            // line 214
            if ($this->getAttribute($context["trait"], "fullyQualifiedStructuralElementName", [])) {
                echo "</a>";
            }
            // line 215
            echo "                            </dd>
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trait'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 217
        echo "
                        ";
        // line 227
        echo "
                        ";
        // line 228
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
                // line 229
                echo "                            ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 230
                    echo "                        <dt>See also</dt>
                            ";
                }
                // line 232
                echo "                            ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 233
                    echo "                                <dd><a href=\"";
                    echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) ? (call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) : ($this->getAttribute($context["tag"], "link", []))), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 235
                echo "                        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 236
        echo "                        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "uses"])) {
                // line 237
                echo "                            ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 238
                    echo "                                <dt>Uses</dt>
                            ";
                }
                // line 240
                echo "                            ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 241
                    echo "                                <dd><a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"]), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 243
                echo "                        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 244
        echo "
                        ";
        // line 246
        echo "                            ";
        // line 247
        echo "                    </dl>
                    <h2>Tags</h2>
                    <table class=\"table table-condensed\">
                    ";
        // line 250
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "uses", 3 => "abstract", 4 => "example", 5 => "method", 6 => "property", 7 => "property-read", 8 => "property-write", 9 => "package", 10 => "subpackage"])) {
                // line 251
                echo "                        <tr>
                            <th>
                                ";
                // line 253
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "
                            </th>
                            <td>
                                ";
                // line 256
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 257
                    echo "                                    ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 258
                    echo "                                    ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 260
                echo "                            </td>
                        </tr>
                    ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 263
            echo "                        <tr><td colspan=\"2\"><em>None found</em></td></tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 265
        echo "                    </table>
                </aside>
            </div>

            ";
        // line 269
        $context["constants"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedConstants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "constants", [])], "method");
        // line 270
        echo "            ";
        if ((twig_length_filter($this->env, ($context["constants"] ?? null)) > 0)) {
            // line 271
            echo "            <a id=\"constants\" name=\"constants\"></a>
            <div class=\"row-fluid\">
                <div class=\"span8 content class\">
                    <h2>Constants</h2>
                </div>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

            ";
            // line 279
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["constants"] ?? null));
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
                // line 280
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
            // line 282
            echo "            ";
        }
        // line 283
        echo "
            ";
        // line 284
        $context["properties"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedProperties", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicProperties", [])], "method")], "method");
        // line 285
        echo "            ";
        if ((twig_length_filter($this->env, ($context["properties"] ?? null)) > 0)) {
            // line 286
            echo "            <a id=\"properties\" name=\"properties\"></a>
            <div class=\"row-fluid\">
                <div class=\"span8 content class\">
                    <h2>Properties</h2>
                </div>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 294
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["properties"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                if (($this->getAttribute($context["property"], "visibility", []) == "public")) {
                    // line 295
                    echo "                ";
                    $this->displayBlock("property", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 297
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["properties"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                if (($this->getAttribute($context["property"], "visibility", []) == "protected")) {
                    // line 298
                    echo "                ";
                    $this->displayBlock("property", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 300
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["properties"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                if (($this->getAttribute($context["property"], "visibility", []) == "private")) {
                    // line 301
                    echo "                ";
                    $this->displayBlock("property", $context, $blocks);
                    echo "
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 303
            echo "            ";
        }
        // line 304
        echo "
            ";
        // line 305
        $context["methods"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")], "method");
        // line 306
        echo "            ";
        if ((twig_length_filter($this->env, ($context["methods"] ?? null)) > 0)) {
            // line 307
            echo "            <a id=\"methods\" name=\"methods\"></a>
            <div class=\"row-fluid\">
                <div class=\"span8 content class\"><h2>Methods</h2></div>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 313
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["methods"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "public")) {
                    // line 314
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
            // line 316
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["methods"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "protected")) {
                    // line 317
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
            // line 319
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["methods"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                if (($this->getAttribute($context["method"], "visibility", []) == "private")) {
                    // line 320
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
            // line 322
            echo "            ";
        }
        // line 323
        echo "        </div>
    </section>

    <div id=\"source-view\" class=\"modal hide fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"source-view-label\" aria-hidden=\"true\">
        <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
            <h3 id=\"source-view-label\">";
        // line 329
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "file", []), "name", []), "html", null, true);
        echo "</h3>
        </div>
        <div class=\"modal-body\">
            <pre data-src=\"";
        // line 332
        echo call_user_func_array($this->env->getFunction('path')->getCallable(), [(("files/" . $this->getAttribute(($context["node"] ?? null), "path", [])) . ".txt")]);
        echo "\" class=\"language-php line-numbers\"></pre>
        </div>
    </div>
";
    }

    // line 189
    public function block_hierarchy_element($context, array $blocks = [])
    {
        // line 190
        echo "                                    ";
        if (($context["class"] ?? null)) {
            // line 191
            echo "                                        ";
            $context["child"] = ($context["class"] ?? null);
            // line 192
            echo "                                        ";
            $context["class"] = $this->getAttribute(($context["class"] ?? null), "parent", []);
            // line 193
            echo "                                        ";
            $this->displayBlock("hierarchy_element", $context, $blocks);
            echo "
                                        <div class=\"namespace-wrapper\">";
            // line 194
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [($context["child"] ?? null)]);
            echo "</div>
                                    ";
        }
        // line 196
        echo "                                ";
    }

    public function getTemplateName()
    {
        return "/class.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1046 => 196,  1041 => 194,  1036 => 193,  1033 => 192,  1030 => 191,  1027 => 190,  1024 => 189,  1016 => 332,  1010 => 329,  1002 => 323,  999 => 322,  986 => 320,  974 => 319,  961 => 317,  949 => 316,  936 => 314,  925 => 313,  917 => 307,  914 => 306,  912 => 305,  909 => 304,  906 => 303,  893 => 301,  881 => 300,  868 => 298,  856 => 297,  843 => 295,  832 => 294,  822 => 286,  819 => 285,  817 => 284,  814 => 283,  811 => 282,  794 => 280,  777 => 279,  767 => 271,  764 => 270,  762 => 269,  756 => 265,  749 => 263,  741 => 260,  732 => 258,  727 => 257,  723 => 256,  717 => 253,  713 => 251,  707 => 250,  702 => 247,  700 => 246,  697 => 244,  687 => 243,  676 => 241,  671 => 240,  667 => 238,  664 => 237,  652 => 236,  642 => 235,  631 => 233,  626 => 232,  622 => 230,  619 => 229,  608 => 228,  605 => 227,  602 => 217,  587 => 215,  583 => 214,  578 => 213,  572 => 212,  569 => 211,  565 => 209,  562 => 208,  545 => 207,  542 => 206,  523 => 204,  519 => 202,  516 => 201,  499 => 200,  492 => 197,  489 => 189,  487 => 188,  483 => 186,  478 => 184,  475 => 183,  473 => 182,  467 => 181,  462 => 178,  456 => 175,  452 => 173,  450 => 172,  447 => 171,  443 => 169,  440 => 168,  436 => 166,  434 => 165,  424 => 157,  417 => 155,  404 => 153,  398 => 152,  394 => 150,  387 => 148,  374 => 146,  368 => 145,  359 => 138,  352 => 136,  339 => 134,  333 => 133,  329 => 131,  322 => 129,  309 => 127,  303 => 126,  297 => 122,  290 => 120,  278 => 118,  273 => 117,  269 => 115,  262 => 113,  249 => 111,  243 => 110,  239 => 108,  232 => 106,  219 => 104,  213 => 103,  195 => 87,  185 => 86,  177 => 84,  172 => 83,  167 => 82,  163 => 80,  160 => 79,  149 => 78,  144 => 76,  140 => 75,  134 => 74,  131 => 73,  127 => 71,  125 => 70,  122 => 69,  117 => 67,  105 => 58,  103 => 57,  99 => 55,  96 => 54,  44 => 4,  41 => 3,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/class.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//class.html.twig");
    }
}
