import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.scss';

registerBlockType('glimpse-post-block/posts', {
    apiVersion: 3,
    title: __('Glimpse Posts', 'glimpse-post-block'),
    icon: 'grid-view',
    category: 'widgets',
    attributes: {
        category: { type: 'string', default: '' },
        tags: { type: 'array', default: [] },
        numberOfPosts: { type: 'number', default: 5 },
        excerptLength: { type: 'number', default: 20 },
        specificPosts: { type: 'array', default: [] },
        showTitle: { type: 'boolean', default: true },
        showImage: { type: 'boolean', default: true },
        showExcerpt: { type: 'boolean', default: true },
        titleLink: { type: 'boolean', default: true },
        buttonLink: { type: 'boolean', default: false },
        buttonText: { type: 'string', default: 'Read More' },
        className: { type: 'string', default: '' }
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        
        // Get available data from window object
        const { categories, tags, posts, i18n } = window.glimpsePostBlock || {};
        
        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Filter', 'glimpse-post-block')} initialOpen={true}>
                        <SelectControl
                            label={__('Category', 'glimpse-post-block')}
                            value={attributes.category}
                            options={[
                                { label: i18n?.selectCategory || __('Select Category', 'glimpse-post-block'), value: '' },
                                ...(categories || []).map(category => ({
                                    label: category.name,
                                    value: category.id
                                }))
                            ]}
                            onChange={(val) => setAttributes({ category: val })}
                        />
                        
                        <SelectControl
                            label={__('Tags', 'glimpse-post-block')}
                            value={attributes.tags}
                            multiple={true}
                            options={[
                                { label: i18n?.selectTags || __('Select Tags', 'glimpse-post-block'), value: '' },
                                ...(tags || []).map(tag => ({
                                    label: tag.name,
                                    value: tag.id
                                }))
                            ]}
                            onChange={(val) => setAttributes({ tags: val })}
                        />
                        
                        <SelectControl
                            label={__('Specific Posts', 'glimpse-post-block')}
                            value={attributes.specificPosts}
                            multiple={true}
                            options={[
                                { label: i18n?.selectPosts || __('Select Specific Posts', 'glimpse-post-block'), value: '' },
                                ...(posts || []).map(post => ({
                                    label: post.title,
                                    value: post.id
                                }))
                            ]}
                            onChange={(val) => setAttributes({ specificPosts: val })}
                        />
                        
                        <RangeControl
                            label={i18n?.numberOfPosts || __('Number of Posts', 'glimpse-post-block')}
                            value={attributes.numberOfPosts}
                            onChange={(val) => setAttributes({ numberOfPosts: val })}
                            min={1}
                            max={20}
                        />
                        
                        <RangeControl
                            label={__('Excerpt Length', 'glimpse-post-block')}
                            value={attributes.excerptLength}
                            onChange={(val) => setAttributes({ excerptLength: val })}
                            min={10}
                            max={100}
                        />
                    </PanelBody>
                    
                    <PanelBody title={__('Display', 'glimpse-post-block')} initialOpen={true}>
                        <ToggleControl
                            label={i18n?.showTitle || __('Show Title', 'glimpse-post-block')}
                            checked={attributes.showTitle}
                            onChange={(val) => setAttributes({ showTitle: val })}
                        />
                        
                        <ToggleControl
                            label={i18n?.showImage || __('Show Featured Image', 'glimpse-post-block')}
                            checked={attributes.showImage}
                            onChange={(val) => setAttributes({ showImage: val })}
                        />
                        
                        <ToggleControl
                            label={i18n?.showExcerpt || __('Show Excerpt', 'glimpse-post-block')}
                            checked={attributes.showExcerpt}
                            onChange={(val) => setAttributes({ showExcerpt: val })}
                        />
                        
                        <ToggleControl
                            label={i18n?.titleLink || __('Link on Title', 'glimpse-post-block')}
                            checked={attributes.titleLink}
                            onChange={(val) => setAttributes({ titleLink: val })}
                        />
                        
                        <ToggleControl
                            label={i18n?.buttonLink || __('Show Read More Button', 'glimpse-post-block')}
                            checked={attributes.buttonLink}
                            onChange={(val) => setAttributes({ buttonLink: val })}
                        />
                        
                        {attributes.buttonLink && (
                            <TextControl
                                label={i18n?.buttonText || __('Button Text', 'glimpse-post-block')}
                                value={attributes.buttonText}
                                onChange={(val) => setAttributes({ buttonText: val })}
                            />
                        )}
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