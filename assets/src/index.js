import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.scss';

registerBlockType('glimpse-post-block/posts', {
    apiVersion: 2,
    title: __('Glimpse Post Block', 'glimpse-post-block'),
    icon: 'grid-view',
    category: 'widgets',
    attributes: {
        category: { type: 'string', default: '' },
        tag: { type: 'string', default: '' },
        postsPerPage: { type: 'number', default: 4 },
        showTitle: { type: 'boolean', default: true },
        showImage: { type: 'boolean', default: true },
        showExcerpt: { type: 'boolean', default: true },
        linkOption: { type: 'string', default: 'title' }
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Filter', 'glimpse-post-block')} initialOpen={true}>
                        <TextControl label={__('Category (slug or id)', 'glimpse-post-block')} value={attributes.category} onChange={(val) => setAttributes({ category: val })} />
                        <TextControl label={__('Tag (slug or id)', 'glimpse-post-block')} value={attributes.tag} onChange={(val) => setAttributes({ tag: val })} />
                        <RangeControl label={__('Posts to show', 'glimpse-post-block')} value={attributes.postsPerPage} onChange={(val) => setAttributes({ postsPerPage: val })} min={1} max={20} />
                    </PanelBody>
                    <PanelBody title={__('Display', 'glimpse-post-block')}>
                        <ToggleControl label={__('Show Title', 'glimpse-post-block')} checked={attributes.showTitle} onChange={(val) => setAttributes({ showTitle: val })} />
                        <ToggleControl label={__('Show Featured Image', 'glimpse-post-block')} checked={attributes.showImage} onChange={(val) => setAttributes({ showImage: val })} />
                        <ToggleControl label={__('Show Excerpt', 'glimpse-post-block')} checked={attributes.showExcerpt} onChange={(val) => setAttributes({ showExcerpt: val })} />
                        <SelectControl label={__('Link option', 'glimpse-post-block')} value={attributes.linkOption} options={[
                            { label: __('Link Title', 'glimpse-post-block'), value: 'title' },
                            { label: __('Read More Button', 'glimpse-post-block'), value: 'button' },
                            { label: __('No Link', 'glimpse-post-block'), value: 'none' }
                        ]} onChange={(val) => setAttributes({ linkOption: val })} />
                    </PanelBody>
                </InspectorControls>

                <div className="glimpse-post-block-editor-preview">
                    <ServerSideRender block="glimpse-post-block/posts" attributes={attributes} />
                </div>
            </>
        );
    },
    save: () => null
});