<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"ROW_CLASS" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_ROW_CLASS"),
		"TYPE" => "TEXT",
		"DEFAULT" => "row",
	),
	"COLUMNS" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_COLUMNS"),
		"TYPE" => "TEXT",
		"DEFAULT" => "4",
	),
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_NAME" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"NAME_TAG" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_NAME_TAG"),
		"TYPE" => "TEXT",
		"DEFAULT" => "H3",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
);
