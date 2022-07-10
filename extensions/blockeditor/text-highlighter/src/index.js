import {
  InspectorControls,
  PanelColorSettings
} from '@wordpress/block-editor';
import {
  registerFormatType,
  applyFormat,
  removeFormat
} from '@wordpress/rich-text';
import {
  addFilter
} from '@wordpress/hooks';

const thData = {
  name: 'blankblanc/text-highlighter',
  className: 'text-highlighter',
  title: 'マーカー',
  label: '選択色',
  colors: [
    {
      name: 'レッド',
      color: '#fc5c5c80',
      class: 'text-highlighter-red'
    },
    {
      name: 'オレンジ',
      color: '#fd992680',
      class: 'text-highlighter-orange'
    },
    {
      name: 'イエロー',
      color: '#f7f70e80',
      class: 'text-highlighter-yellow'
    },
    {
      name: 'グリーン',
      color: '#6ce76880',
      class: 'text-highlighter-green'
    },
    {
      name: 'ブルー',
      color: '#7298e980',
      class: 'text-highlighter-blue'
    },
    {
      name: 'パープル',
      color: '#c561ec80',
      class: 'text-highlighter-purple'
    },
    {
      name: 'ピンク',
      color: '#f77dd280',
      class: 'text-highlighter-pink'
    },
    {
      name: 'スカイブルー',
      color: '#89d0ff80',
      class: 'text-highlighter-sky'
    },
    {
      name: 'グレー',
      color: '#cacaca80',
      class: 'text-highlighter-gray'
    }
  ]
};

let registered = false;
const addTextHighlighter = (settings) => {
  if (!registered) {
    registerFormatType(thData.name, {
      title: thData.title,
      tagName: 'em',
      className: `${thData.className}`,
      attributes: {
        class: 'class'
      },
      edit({
        isActive,
        value,
        onChange,
        activeAttributes,
      }) {
        let activeColor;
        if (isActive) {
          const currentColor = thData.colors.find(el => el.class == activeAttributes.class);
          activeColor = currentColor.color;
        }
        return (
          <InspectorControls>
            <PanelColorSettings
              title={thData.title}
              initialOpen={true}
              colorSettings={[{
                value: activeColor,
                onChange: (color) => {
                  if (color) {
                    const currentColor = thData.colors.find(el => el.color == color);
                    onChange(applyFormat(value, {
                      type: thData.name,
                      attributes: {
                        class: `${currentColor.class}`
                      }
                    }));
                  } else {
                    onChange(removeFormat(value, thData.name));
                  }
                },
                label: thData.label,
                colors: thData.colors
              }]}
            />
          </InspectorControls>
        )
      }
    });
  }
  registered = true;
  return settings;
};

addFilter(
  'blocks.registerBlockType',
  thData.name,
  addTextHighlighter
);
