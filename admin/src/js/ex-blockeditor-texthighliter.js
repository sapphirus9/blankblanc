/**
 * WordPress Gutenbergのリッチテキストツールバーにドロップダウンメニューボタン追加
 * 参考: https://celtislab.net/archives/20200319/wordpress-richtext-toolbar-button/
 */
'use strict';
(function () {
  const { Fragment, createElement } = wp.element;
  const { registerFormatType, toggleFormat } = wp.richText;
  const { RichTextToolbarButton, RichTextShortcut, BlockFormatControls } = wp.blockEditor;
  const { Fill, Slot, ToolbarGroup, ToolbarButton, ToolbarDropdownMenu } = wp.components;
  const { displayShortcut } = wp.keycodes;
  const el = createElement;
  function BbRichTextToolbarButton({ name, shortcutType, shortcutCharacter, ...props }) {
    let shortcut;
    let fillName = 'BbToolbarControls';
    if ( name ) {
      fillName += `.${ name }`;
    }
    if ( shortcutType && shortcutCharacter ) {
      shortcut = displayShortcut[ shortcutType ]( shortcutCharacter );
    }
    return (
      el( Fill,
        { name: fillName },
        el( ToolbarButton,
          props,
          { shortcut: shortcut }
        ),
      )
    );
  };

  registerFormatType( 'blankblanc/dropdown', {
    title: 'buttons',
    tagName: 'dropdown',
    className: null,
    edit({ isActive, value, onChange }) {
      return (
        el( BlockFormatControls,
          {},
          el( 'div',
            { className: 'editor-format-toolbar block-editor-format-toolbar' },
            el( ToolbarGroup,
              {},
              el( Slot,
                { name: 'BbToolbarControls' },
                (fills) => {
                  return ( fills.length !== 0 &&
                    el( ToolbarDropdownMenu,
                      { icon: 'admin-customizer',
                        label: 'マーカーを引く',
                        hasArrowIndicator: true,
                        popoverProps: { position: 'bottom right' },
                        controls: fills.map( ( [ { props } ] ) => props ),
                      }
                    )
                  );
                }
              )
            ),
          )
        )
      );
    }
  });

  const tagTypes = [
    {
      title: 'マーカー: 赤',
      tag:   'span',
      class: 'text-highlighter-red',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: オレンジ',
      tag:   'span',
      class: 'text-highlighter-orange',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: 黄',
      tag:   'span',
      class: 'text-highlighter-yellow',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: 緑',
      tag:   'span',
      class: 'text-highlighter-green',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: 青',
      tag:   'span',
      class: 'text-highlighter-blue',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: 紫',
      tag:   'span',
      class: 'text-highlighter-purple',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: ピンク',
      tag:   'span',
      class: 'text-highlighter-pink',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: 空色',
      tag:   'span',
      class: 'text-highlighter-sky',
      icon:  'admin-customizer'
    },
    {
      title: 'マーカー: グレー',
      color: '#f00',
      tag:   'span',
      class: 'text-highlighter-gray',
      icon:  'admin-customizer'
    },
  ];

  tagTypes.map((idx) => {
    let type = 'blankblanc/richtext-' + idx.tag;
    if (idx.class !== null) {
      type += '-' + idx.class;
    }
    registerFormatType( type, {
      title: idx.title,
      tagName: idx.tag,
      className: idx.class,
      edit({ isActive, value, onChange }) {
        return (
          el( Fragment,
            {},
            el( BbRichTextToolbarButton,
              { icon: idx.icon,
                title: idx.title,
                isActive: isActive,
                className: idx.class,
                onClick: () => onChange( toggleFormat(value, { type: type })),
              }
            )
          )
        );
      },
    });
  })
}());
