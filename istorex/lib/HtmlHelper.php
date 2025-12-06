<?php
// lib/HtmlHelper.php
// 严格遵守规则：使用 HTML Helpers 生成控件 

class Html
{

    // 生成 <input> 标签
    public static function input($type, $name, $value = '', $attributes = [])
    {
        $attrString = self::buildAttributes($attributes);
        // 自动保留表单提交后的值 (Sticky form)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$name]) && $type !== 'password') {
            $value = htmlspecialchars($_POST[$name]);
        }
        return "<input type=\"$type\" name=\"$name\" value=\"$value\" $attrString>";
    }

    // 生成普通按钮
    public static function button($text, $type = 'submit', $attributes = [])
    {
        $attrString = self::buildAttributes($attributes);
        return "<button type=\"$type\" $attrString>$text</button>";
    }

    // 生成超链接
    public static function link($text, $href, $attributes = [])
    {
        $attrString = self::buildAttributes($attributes);
        return "<a href=\"$href\" $attrString>$text</a>";
    }

    // 生成图片标签
    public static function img($src, $alt = '', $attributes = [])
    {
        $attrString = self::buildAttributes($attributes);
        return "<img src=\"$src\" alt=\"$alt\" $attrString>";
    }

    // 内部辅助函数：将数组转换为 HTML 属性字符串
    private static function buildAttributes($attributes)
    {
        $html = '';
        foreach ($attributes as $key => $val) {
            // 如果只有 key 没有值 (例如 required, checked)
            if (is_int($key)) {
                $html .= " $val";
            } else {
                $html .= " $key=\"$val\"";
            }
        }
        return $html;
    }
}
