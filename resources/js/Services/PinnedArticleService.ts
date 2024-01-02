export async function pin(data: Object) {
    const res = await fetch('/api/pinned-articles', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    })

    return res.json()
}

export async function unpin(articleId: string) {
    const res = await fetch(`/api/pinned-articles/${articleId}`, {
        method: "DELETE",
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
          _method: "DELETE"
        })
    })

    return res.json()
}

export default {
    pin,
    unpin,
}
