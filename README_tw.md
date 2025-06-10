# WP Markdown

## 插件資訊
- 貢獻者：Danny
- 標籤：markdown, editor, content, formatting, security
- 最低需求：WordPress 5.2
- 測試版本：6.4
- 穩定版本：2.0.0
- PHP 需求：7.2
- 授權：GPLv2 或更新版本
- 授權網址：https://www.gnu.org/licenses/gpl-2.0.html

一個現代化的 WordPress 插件，提供 Markdown 處理功能，並支援 Markdown Extra 特性。

## 功能說明

WP Markdown 是一個強大且輕量級的插件，為您的 WordPress 網站帶來 Markdown 支援。它可以處理文章、頁面和評論中的 Markdown 內容，讓您能夠輕鬆使用 Markdown 語法來撰寫和格式化內容。

### 主要功能

* 完整的 Markdown Extra 支援
* 處理文章、頁面和評論
* 正確處理腳註
* 維持適當的 HTML 結構
* 輕量級且快速
* 無外部依賴
* 相容於 WordPress 5.2 及以上版本
* 安全且經過充分測試的代碼

### Markdown Extra 特性

* 表格
* 腳註
* 定義列表
* 程式碼區塊
* 縮寫
* 更多功能！

### 使用範例

```markdown
# 這是標題

這是一個包含**粗體**和*斜體*文字的段落。

- 列表項目 1
- 列表項目 2

[連結文字](https://example.com)

> 這是引用區塊

| 表格 | 標題 |
|------|------|
| 儲存格 | 儲存格 |

[^1]: 這是腳註
```

## 安裝說明

1. 將 `wp-markdown` 資料夾上傳到 `/wp-content/plugins/` 目錄
2. 透過 WordPress 的「插件」選單啟用插件
3. 開始使用 Markdown 語法撰寫內容

## 常見問題

### 我需要了解 Markdown 才能使用這個插件嗎？

是的，您需要熟悉 Markdown 語法才能有效使用這個插件。不過，Markdown 非常容易學習，網路上有許多學習資源。

### 這個插件會影響我現有的內容嗎？

不會，插件只會在顯示內容時進行處理。您的原始內容在資料庫中保持不變。

### 這個插件可以與其他格式化插件一起使用嗎？

插件設計為可以與其他格式化插件並存，但您應該測試與您的特定設置的相容性。

### 這個插件安全嗎？

是的，插件使用經過充分測試的 PHP Markdown & Extra 函式庫，並遵循 WordPress 安全最佳實踐。它不會收集任何用戶數據或發送外部請求。所有處理都在您的伺服器上本地完成。

## 更新日誌

### 2.0.0
* 初始發布
* 完整的 Markdown Extra 支援
* WordPress 5.2+ 相容性
* PHP 7.2+ 需求
* 注重安全的實現

## 升級通知

### 2.0.0
WP Markdown 的初始發布，提供完整的 Markdown Extra 支援。

## 致謝

本插件基於 Michel Fortin 的 PHP Markdown & Extra。
原始 Markdown 由 John Gruber 開發。

## 授權

本插件採用 GPL v2 或更新版本授權。

WP Markdown 基於 PHP Markdown & Extra
版權所有 (c) 2004-2013 Michel Fortin <http://michelf.ca/>
基於 Markdown
版權所有 (c) 2003-2006 John Gruber <http://daringfireball.net/> 