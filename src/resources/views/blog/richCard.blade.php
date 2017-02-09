<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ app_url() . '/blog/' . $blog->slug }}"
  },
  "image": {
    "@type": "ImageObject",
    "url": "{{ app_url() . $blog->user->image }}",
    "height": "{{ getimagesize(public_path() . $blog->user->image)[1] }}",
    "width":  "{{ getimagesize(public_path() . $blog->user->image)[0] }}"
  },
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
    "logo": {
            "@type": "ImageObject",
            "url": "{{ app_url() . $blog->user->image }}"
        }
    }
  },
  "description": "{{ $blog->title }}"
}
</script>