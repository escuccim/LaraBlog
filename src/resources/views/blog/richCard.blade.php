<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ app_url() . '/blog/' . $blog->slug }}"
  },
  @if($blog->image || $blog->user->image)
      "image": {
        "@type": "ImageObject",
        "url": "{{  asset($blog->image ? $blog->image : $blog->user->image) }}",
        "height": "{{ $blog->image_height ? $blog->image_height : '125' }}",
        "width":  "{{ $blog->image_width ? $blog->image_width : '100' }}"
      },
  @endif
  "headline": "{{ $blog->title }}",
  "datePublished": "{{ date(DATE_ATOM, strtotime($blog->published_at)) }}",
  "dateModified": "{{ date(DATE_ATOM, strtotime($blog->updated_at)) }}",
  "author": {
    "@type": "Person",
    "name": "{{ $blog->user->name }}"
  },
   "publisher": {
    "@type": "Organization",
    "name": "{{ app_name() }}",
    @if(file_exists(public_path() . '/images/logo.png'))
    "logo": {
            "@type": "ImageObject",
            "url": "{{ asset(config('blog.logo')) }}"
        }
    @endif
    }
  },
  "description": "{{ $blog->title }}"
}
</script>