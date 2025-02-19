import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import metadata from './block.json';

registerBlockType(metadata.name, {
    ...metadata,
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        const { title, instructions } = attributes;

        return (
            <div {...blockProps}>
                <RichText
                    tagName="h2"
                    value={title}
                    onChange={(title) => setAttributes({ title })}
                    placeholder={__('Recipe Title', 'dvnl-family-recipe-book')}
                />
                <RichText
                    tagName="div"
                    value={instructions}
                    onChange={(instructions) => setAttributes({ instructions })}
                    placeholder={__('Write recipe instructions...', 'dvnl-family-recipe-book')}
                />
            </div>
        );
    },
    save: ({ attributes }) => {
        const blockProps = useBlockProps.save();
        const { title, instructions } = attributes;

        return (
            <div {...blockProps}>
                <RichText.Content tagName="h2" value={title} />
                <RichText.Content tagName="div" value={instructions} />
            </div>
        );
    },
});