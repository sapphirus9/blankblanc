/**
 * blankblanc/text-highlighter
 * 選択した文字の範囲にカラーマーカー（9色）引くことができます
 */
import { ColorPalette, RichTextToolbarButton } from '@wordpress/block-editor';
import { Popover, TabPanel } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { Icon } from '@wordpress/icons';
import {
  applyFormat,
  registerFormatType,
  toggleFormat,
  useAnchor,
} from '@wordpress/rich-text';

const thData = {
  name: 'blankblanc/text-highlighter',
  className: 'text-highlighter',
  title: 'マーカー',
  colors: [
    {
      name: 'レッド',
      color: '#fc5c5c80',
      class: 'text-highlighter-red',
    },
    {
      name: 'オレンジ',
      color: '#fd992680',
      class: 'text-highlighter-orange',
    },
    {
      name: 'イエロー',
      color: '#f7f70e80',
      class: 'text-highlighter-yellow',
    },
    {
      name: 'グリーン',
      color: '#6ce76880',
      class: 'text-highlighter-green',
    },
    {
      name: 'ブルー',
      color: '#7298e980',
      class: 'text-highlighter-blue',
    },
    {
      name: 'パープル',
      color: '#c561ec80',
      class: 'text-highlighter-purple',
    },
    {
      name: 'ピンク',
      color: '#f77dd280',
      class: 'text-highlighter-pink',
    },
    {
      name: 'スカイブルー',
      color: '#89d0ff80',
      class: 'text-highlighter-sky',
    },
    {
      name: 'グレー',
      color: '#cacaca80',
      class: 'text-highlighter-gray',
    },
  ],
};

const TextHighlighter = (props) => {
  const [showPopover, setShowPopover] = useState(false);
  const { contentRef, isActive, value, onChange, activeAttributes } = props;
  const anchorRef = useAnchor({
    editableContentElement: contentRef.current,
    value,
  });

  let selectedColor;
  const iconStyle = {
    fill: '',
    background: '',
    boxShadow: '',
  };
  if (isActive) {
    // 選択されているカラー情報を取得
    const selectedClass = thData.colors.find((el) => el.class === activeAttributes.class);
    selectedColor = selectedClass.color;
    iconStyle.fill = '#000';
    iconStyle.backgroundColor = '#fff';
    iconStyle.background = `linear-gradient(180deg, ${selectedColor.substring(0, 7)}00 50%, ${selectedColor} 50%)`;
  }
  return (
    <>
      <RichTextToolbarButton
        icon={
          <>
            <Icon
              icon={
                <svg viewBox="-50 -50 612 612" style="width:24px;height:24px;">
                  <g>
                    <path d="M208.125,399.438c0,0,9.656-15.688,21.266-34.563L75.469,270.156C63.859,289,54.188,304.719,54.188,304.719s27.797,60.406-9.906,121.656l29.844,18.375l29.844,18.375C141.656,401.844,208.125,399.438,208.125,399.438z"></path>
                    <path d="M389.531,104.688c6.031-9.844,2.969-22.719-6.859-28.781L264.359,3.109C260.938,1,257.156,0,253.422,0c-7.016,0-13.875,3.531-17.828,9.953L83.188,257.594l153.938,94.719L389.531,104.688z M128.344,246.844L257.313,37.313l98.016,60.313L226.375,307.156L128.344,246.844z"></path>
                    <polygon points="22.531,488.641 74.188,488.641 87.484,467.031 48.219,442.875"></polygon>
                    <path d="M482.406,484.453H117.844c-3.906,0-7.063,3.156-7.063,7.047v13.438c0,3.906,3.156,7.063,7.063,7.063h364.563c3.906,0,7.063-3.156,7.063-7.063V491.5C489.469,487.609,486.313,484.453,482.406,484.453z"></path>
                  </g>
                </svg>
              }
              style={iconStyle}
            />
          </>
        }
        title={thData.title}
        isActive={isActive}
        role={'menuitemcheckbox'}
        onClick={() => {
          setShowPopover(true);
        }}
      />
      {showPopover && (
        <Popover
          anchor={anchorRef}
          className={'components-inline-color-popover'}
          onClose={() => setShowPopover(false)}
        >
          <TabPanel
            className={`${thData.className}-tabs}`}
            tabs={[
              {
                name: 'tab1',
                title: thData.title,
                className: 'tab-one'
              }
            ]}
          >
            {() => (
              <ColorPalette
                colors={thData.colors}
                value={selectedColor}
                disableCustomColors={true}
                onChange={(color) => {
                  setShowPopover(false);
                  if (color) {
                    // 選択したカラー情報を取得
                    const currentClass = thData.colors.find((el) => el.color === color);
                    onChange(
                      applyFormat(value, {
                        type: thData.name,
                        attributes: {
                          class: currentClass.class
                        },
                      })
                    );
                  } else {
                    onChange(
                      toggleFormat(value, {
                        type: thData.name,
                      })
                    );
                  }
                }}
              />
            )}
          </TabPanel>
        </Popover>
      )}
    </>
  )
}

registerFormatType(thData.name, {
  title: thData.title,
  tagName: 'mark',
  className: thData.className,
  attributes: {
    class: 'class'
  },
  edit: TextHighlighter
});
