@isset($paginator)
<style>
.paginator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2.5rem 0;
}
.paginator ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
.paginator__item, .paginator__list {
    display: flex;
    align-items: center;
}
.paginator__item {
    justify-content: center;
    opacity: .75;
    margin: 0 0.3125rem;
    width: 2rem;
    height: 2rem;
    font-size: 1rem;
    color: #3b3b3b;
    border: 0.0625rem solid #3b3b3b;
    border-radius: 3.125rem;
    transition: opacity .25s ease;
    cursor: pointer;
}
.paginator__item--active {
    opacity: 1;
    color: #fff;
    background-color: #3b3b3b;
}
.paginator__item a {
    width: 100%;
    line-height: 2rem;
    text-align: center;
}
.paginator__next, .paginator__prev {
    opacity: .75;
    font-size: 1.5625rem;
    color: #3b3b3b;
    transition: opacity .25s ease;
}
.paginator__next {
    margin-left: 0.3125rem;
}
.paginator__prev {
    margin-right: 0.3125rem;
}
</style>
<div class="paginator">
    {!! $paginator !!}
</div>
@endisset