document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('.page-rating-container');
  if (!container) return;

  const stars = container.querySelectorAll('.star');
  const postId = customAjax.post_id;
  const message = container.querySelector('.rating-message');
  const summary = container.querySelector('.rating-summary');
  let avgRating = parseFloat(container.getAttribute('data-avg-rating')) || 0;

  function updateStars(rating) {
    stars.forEach((star, index) => {
      star.classList.toggle('filled', index < Math.round(rating));
    });
  }

  updateStars(avgRating);

  stars.forEach((star, idx) => {
    star.addEventListener('mouseenter', () => {
      updateStars(idx + 1);
    });

    star.addEventListener('mouseleave', () => {
      updateStars(avgRating);
    });

    star.addEventListener('click', () => {
        const restBase = customAjax.rest_url.replace(/\/$/, '');
        fetch(`${restBase}/rate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          post_id: postId,
          rating: idx + 1
        })
      })
        .then(res => res.json())
        .then(data => {
          if (data && data.total) {
            avgRating = parseFloat(data.total.avg);
            const count = data.total.count;

            container.setAttribute('data-avg-rating', avgRating.toFixed(1));
            updateStars(avgRating);
            summary.querySelector('.average strong').textContent = avgRating.toFixed(1);
            summary.querySelector('.count').textContent = `(از ${count} رأی)`;

              message.textContent = '✅ رأی شما ثبت شد.';
              container.classList.add('show-message', 'rated'); 

          } else {
            message.textContent = '❌ خطا در ثبت رأی.';
              container.classList.add('show-message');
          }
        })
        .catch(() => {
          message.textContent = '⚠️ خطا در ارتباط با سرور.';
            container.classList.add('show-message');
        });
    });
  });
});
